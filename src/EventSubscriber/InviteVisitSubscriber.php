<?php

namespace WechatMiniProgramShareBundle\EventSubscriber;

use Hashids\Hashids;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService as DoctrineService;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramAuthBundle\Event\CodeToSessionResponseEvent;
use WechatMiniProgramAuthBundle\Service\UserTransformService;
use WechatMiniProgramBundle\Event\LaunchOptionsAware;
use WechatMiniProgramBundle\Service\LaunchOptionHelper;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Event\InviteUserEvent;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;
use Yiisoft\Arrays\ArrayHelper;

/**
 * 邀请和受邀的信息记录
 */
#[WithMonologChannel(channel: 'wechat_mini_program_share')]
readonly class InviteVisitSubscriber
{
    public function __construct(
        private DoctrineService $doctrineService,
        private UserLoaderInterface $userLoader,
        private UserTransformService $userTransformService,
        private LoggerInterface $logger,
        #[Autowire(service: 'wechat-mini-program-share.hashids')] private Hashids $hashids,
        private EventDispatcherInterface $eventDispatcher,
        private LaunchOptionHelper $launchOptionHelper,
    ) {
    }

    #[AsEventListener]
    public function onCodeToSessionRequest(CodeToSessionResponseEvent $event): void
    {
        $query = $this->launchOptionHelper->parseEvent($event)->getAttributes();
        if (!isset($query[WechatMiniProgramShareBundle::PARAM_KEY])) {
            $this->logger->warning('找不到必要的参数，不处理', ['query' => $query]);

            return;
        }

        $decoded = $this->decodeShareParam($query[WechatMiniProgramShareBundle::PARAM_KEY]);
        if (null === $decoded) {
            return;
        }

        $users = $this->loadUsers($decoded);
        if (null === $users) {
            return;
        }

        [$bizUser, $wechatUser] = $users;
        $log = $this->createInviteVisitLog($event, $bizUser, $wechatUser, $decoded);
        if (null === $log) {
            return;
        }

        $this->saveLogAndDispatchEvent($log);
    }

    /**
     * 从启动参数中组装出当前路径
     */
    private function findPath(Event $event): string
    {
        if (!in_array(LaunchOptionsAware::class, class_uses($event), true)) {
            return '';
        }

        // 检查事件是否实现了必要的方法
        if (!method_exists($event, 'getEnterOptions') || !method_exists($event, 'getLaunchOptions')) {
            return '';
        }

        $options = $event->getEnterOptions();
        if (null === $options || [] === $options || '' === $options) {
            $options = $event->getLaunchOptions();
        }

        if (!is_array($options) && !is_object($options)) {
            return '';
        }

        $pathValue = ArrayHelper::getValue($options, 'path', '');
        $path = is_string($pathValue) ? $pathValue : '';

        $queryData = ArrayHelper::getValue($options, 'query');
        $query = is_array($queryData) ? http_build_query($queryData) : '';

        return $path . ('' === $query ? '' : "?{$query}");
    }

    /**
     * @return array{string|int, int}|null
     */
    private function decodeShareParam(mixed $param): ?array
    {
        $decoded = [];
        try {
            if (is_string($param)) {
                $decoded = $this->hashids->decode($param);
            }
        } catch (\Throwable $exception) {
        }

        if ([] === $decoded && is_string($param)) {
            $decoded = explode(',', $param);
        }

        $this->logger->info('获取邀请分享参数', ['param' => $param, 'decoded' => $decoded]);

        if (2 !== count($decoded)) {
            $this->logger->warning('解密数据格式不一致', ['decoded' => $decoded]);

            return null;
        }

        return [$decoded[0], (int) $decoded[1]];
    }

    /**
     * @param array{string|int, int} $decoded
     *
     * @return array{UserInterface, User}|null
     */
    private function loadUsers(array $decoded): ?array
    {
        $bizUser = $this->userLoader->loadUserByIdentifier((string) $decoded[0]);
        if (null === $bizUser) {
            $this->logger->warning('查找不到BizUser', ['decoded' => $decoded]);

            return null;
        }

        $wechatUser = $this->userTransformService->transformToWechatUser($bizUser);
        if (null === $wechatUser) {
            $this->logger->warning('查找不到小程序User', ['decoded' => $decoded]);

            return null;
        }

        return [$bizUser, $wechatUser];
    }

    /**
     * @param array{string|int, int} $decoded
     */
    private function createInviteVisitLog(
        CodeToSessionResponseEvent $event,
        UserInterface $bizUser,
        User $wechatUser,
        array $decoded,
    ): ?InviteVisitLog {
        $shareOpenId = $wechatUser->getOpenId();
        $visitOpenId = $event->getWechatUser()->getOpenId();

        if ($shareOpenId === $visitOpenId) {
            $this->logger->warning('分享人是不能邀请自己的', [
                'shareOpenId' => $shareOpenId,
                'visitOpenId' => $visitOpenId,
            ]);

            return null;
        }

        $log = new InviteVisitLog();
        $log->setShareOpenId($shareOpenId);
        $log->setVisitOpenId($visitOpenId);
        $log->setLaunchOptions($event->getLaunchOptions());
        $log->setEnterOptions($event->getEnterOptions());
        $log->setShareUser($bizUser);

        $shareTime = \DateTimeImmutable::createFromFormat('U', (string) $decoded[1]);
        if (false === $shareTime) {
            $shareTime = new \DateTimeImmutable();
        }
        $log->setShareTime($shareTime);

        $log->setVisitTime(new \DateTimeImmutable());
        $log->setVisitPath($this->findPath($event));
        $log->setVisitUser($event->getBizUser());
        $log->setNewUser($event->isNewUser());

        return $log;
    }

    private function saveLogAndDispatchEvent(InviteVisitLog $log): void
    {
        try {
            $this->doctrineService->asyncInsert($log);

            $event = new InviteUserEvent();
            $event->setInviteVisitLog($log);
            $this->eventDispatcher->dispatch($event);
        } catch (\Throwable $exception) {
            $this->logger->error('保存邀请访问记录时出错', [
                'exception' => $exception,
                'log' => $log,
            ]);
        }
    }
}
