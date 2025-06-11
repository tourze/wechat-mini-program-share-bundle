<?php

namespace WechatMiniProgramShareBundle\EventSubscriber;

use Carbon\Carbon;
use Hashids\Hashids;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService as DoctrineService;
use WechatMiniProgramAuthBundle\Event\CodeToSessionResponseEvent;
use WechatMiniProgramAuthBundle\Repository\UserRepository;
use WechatMiniProgramBundle\Event\LaunchOptionsAware;
use WechatMiniProgramBundle\Service\LaunchOptionHelper;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Event\InviteUserEvent;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;
use Yiisoft\Arrays\ArrayHelper;

/**
 * 邀请和受邀的信息记录
 */
class InviteVisitSubscriber
{
    public function __construct(
        private readonly DoctrineService $doctrineService,
        private readonly UserLoaderInterface $userLoader,
        private readonly UserRepository $userRepository,
        private readonly LoggerInterface $logger,
        #[Autowire(service: 'wechat-mini-program-share.hashids')] private readonly Hashids $hashids,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LaunchOptionHelper $launchOptionHelper,
    ) {
    }

    #[AsEventListener]
    public function onCodeToSessionRequest(CodeToSessionResponseEvent $event): void
    {
        // 没有带参数的话，不处理
        $query = $this->launchOptionHelper->parseEvent($event)->getAttributes();
        if (!isset($query[WechatMiniProgramShareBundle::PARAM_KEY])) {
            $this->logger->warning('找不到必要的参数，不处理', [
                'query' => $query,
            ]);

            return;
        }

        $param = $query[WechatMiniProgramShareBundle::PARAM_KEY];
        $decoded = [];
        try {
            $decoded = $this->hashids->decode($param);
        } catch (\Throwable $exception) {
        }
        if (!$decoded) {
            // 兼容一次
            $decoded = explode(',', $param);
        }

        $this->logger->info('获取邀请分享参数', [
            'param' => $param,
            'decoded' => $decoded,
            'event' => $event,
        ]);
        if (2 !== count($decoded)) {
            $this->logger->warning('解密数据格式不一致', [
                'decoded' => $decoded,
            ]);

            return;
        }

        $bizUser = $this->userLoader->loadUserByIdentifier($decoded[0]);
        if (!$bizUser) {
            $this->logger->warning('查找不到BizUser', [
                'decoded' => $decoded,
            ]);

            return;
        }

        $wechatUser = $this->userRepository->transformToWechatUser($bizUser);
        if (!$wechatUser) {
            $this->logger->warning('查找不到小程序User', [
                'decoded' => $decoded,
            ]);

            return;
        }

        $log = new InviteVisitLog();

        $log->setShareOpenId($wechatUser->getOpenId());
        $log->setVisitOpenId($event->getWechatUser()->getOpenId());

        if ($log->getShareOpenId() === $log->getVisitOpenId()) {
            $this->logger->warning('分享人是不能邀请自己的', [
                'shareOpenId' => $log->getShareOpenId(),
                'visitOpenId' => $log->getVisitOpenId(),
            ]);

            return;
        }

        $log->setLaunchOptions($event->getLaunchOptions());
        $log->setEnterOptions($event->getEnterOptions());

        $log->setShareUser($bizUser);
        $log->setShareTime(Carbon::createFromTimestamp($decoded[1], date_default_timezone_get()));

        $log->setVisitTime(Carbon::now());
        $log->setVisitPath($this->findPath($event));
        $log->setVisitUser($event->getBizUser());
        $log->setNewUser($event->isNewUser());

        try {
            $this->doctrineService->asyncInsert($log);

            // 派发事件
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

    /**
     * 从启动参数中组装出当前路径
     */
    private function findPath(Event $event): string
    {
        if (!in_array(LaunchOptionsAware::class, class_uses($event))) {
            return '';
        }

        /** @var LaunchOptionsAware $event */
        $options = $event->getEnterOptions();
        if (empty($options)) {
            $options = $event->getLaunchOptions();
        }

        $path = ArrayHelper::getValue($options, 'path', '');

        $query = (array) ArrayHelper::getValue($options, 'query');
        $query = http_build_query($query);

        return $path . (empty($query) ? '' : "?{$query}");
    }
}
