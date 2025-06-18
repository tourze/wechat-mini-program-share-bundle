<?php

namespace WechatMiniProgramShareBundle\Procedure;

use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService as DoctrineService;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Procedure\BaseProcedure;
use WechatMiniProgramBundle\Procedure\LaunchOptionsAware;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;

/**
 * 这个接口是给中转页调用的，前端需要确认起码 code2session流程跑完了
 */
#[MethodTag('微信小程序')]
#[MethodDoc('获取分享码详情')]
#[MethodExpose('GetWechatMiniProgramShareCodeInfo')]
#[WithMonologChannel('procedure')]
class GetWechatMiniProgramShareCodeInfo extends BaseProcedure
{
    use LaunchOptionsAware;

    #[MethodParam('分享码ID')]
    public string $id;

    public function __construct(
        private readonly ShareCodeRepository $codeRepository,
        private readonly DoctrineService $doctrineService,
        private readonly Security $security,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function execute(): array
    {
        $code = $this->codeRepository->find($this->id);
        if ($code === null) {
            throw new ApiException('找不到分享码');
        }

        if (!$code->isValid()) {
            throw new ApiException('分享码已无效');
        }

        $log = new ShareVisitLog();
        $log->setCode($code);
        $log->setEnvVersion($code->getEnvVersion());
        $log->setLaunchOptions($this->launchOptions);
        $log->setEnterOptions($this->enterOptions);
        if ($this->security->getUser() !== null) {
            $log->setUser($this->security->getUser());
        }

        // 这里只处理了默认的情形，如果要跳转到tab页，需要自己订阅事件来进行处理
        $url = $code->getLinkUrl();
        $url = trim($url, '/');
        $url = "/{$url}";

        $log->setResponse([
            '__redirectTo' => [
                'url' => $url,
            ],
        ]);

        $tabPages = [
            '/pages/index/index',
            '/pages/block/block',
            '/pages/validate/validate',
            '/pages/myCenter/myCenter',
            '/pages/my/index',
        ];
        foreach ($tabPages as $tabPage) {
            if ((bool) str_starts_with($url, $tabPage)) {
                $log->setResponse([
                    '__reLaunch' => [
                        'url' => $url,
                    ],
                ]);
                break;
            }
        }

        try {
            $this->doctrineService->asyncInsert($log);
        } catch (\Throwable $exception) {
            $this->logger->error('保存记录时发生错误', [
                'log' => $log,
                'exception' => $exception,
            ]);
        }

        return $log->getResponse();
    }
}
