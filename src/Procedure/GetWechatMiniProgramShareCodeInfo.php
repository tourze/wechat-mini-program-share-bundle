<?php

namespace WechatMiniProgramShareBundle\Procedure;

use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService as DoctrineService;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use Tourze\JsonRPC\Core\Result\ArrayResult;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Procedure\BaseProcedure;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;
use WechatMiniProgramShareBundle\Param\GetWechatMiniProgramShareCodeInfoParam;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;

#[MethodTag(name: '微信小程序')]
#[MethodDoc(summary: '获取启动分享码详情', description: '这个接口是给中转页调用的，前端需要确认起码 code2session 流程跑完了')]
#[MethodExpose(method: 'GetWechatMiniProgramShareCodeInfo')]
#[WithMonologChannel(channel: 'procedure')]
class GetWechatMiniProgramShareCodeInfo extends BaseProcedure
{
    public function __construct(
        private readonly ShareCodeRepository $codeRepository,
        private readonly DoctrineService $doctrineService,
        private readonly Security $security,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @phpstan-param GetWechatMiniProgramShareCodeInfoParam $param
     */
    public function execute(GetWechatMiniProgramShareCodeInfoParam|RpcParamInterface $param): ArrayResult
    {
        $code = $this->codeRepository->find($param->id);
        if (null === $code) {
            throw new ApiException('找不到分享码');
        }

        if (false === $code->isValid()) {
            throw new ApiException('分享码已无效');
        }

        $log = new ShareVisitLog();
        $log->setCode($code);
        $log->setEnvVersion($code->getEnvVersion());
        $log->setLaunchOptions($param->launchOptions);
        $log->setEnterOptions($param->enterOptions);
        if (null !== $this->security->getUser()) {
            $log->setUser($this->security->getUser());
        }

        // 这里只处理了默认的情形，如果要跳转到tab页，需要自己订阅事件来进行处理
        $url = $code->getLinkUrl();
        if (null === $url) {
            $url = '';
        }
        $url = trim($url, '/');
        $url = "/{$url}";

        $log->setResponse([
            'url' => $url,
        ]);

        try {
            $this->doctrineService->asyncInsert($log);
        } catch (\Throwable $exception) {
            $this->logger->error('保存记录时发生错误', [
                'log' => $log,
                'exception' => $exception,
            ]);
        }

        return new ArrayResult($log->getResponse());
    }
}
