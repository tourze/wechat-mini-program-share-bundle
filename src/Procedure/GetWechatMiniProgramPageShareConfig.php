<?php

namespace WechatMiniProgramShareBundle\Procedure;

use Carbon\Carbon;
use Hashids\Hashids;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCCacheBundle\Procedure\CacheableProcedure;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

#[MethodTag('微信小程序')]
#[MethodDoc('获取微信小程序页面分享路径参数', '在这个接口里，我们额外为所有路径增加一个分享渠道参数，用于记录分享轨迹')]
#[MethodExpose('GetWechatMiniProgramPageShareConfig')]
class GetWechatMiniProgramPageShareConfig extends CacheableProcedure
{
    #[MethodParam('当前页面路径')]
    public string $path;

    #[MethodParam('当前页面参数')]
    public array $params = [];

    #[MethodParam('记录的是默认的分享配置')]
    public array $config = [];

    public function __construct(
        #[Autowire(service: 'wechat-mini-program-share.hashids')] private readonly Hashids $hashids,
        private readonly Security $security,
    ) {
    }

    public function execute(): array
    {
        if (!isset($this->config['path'])) {
            return [];
        }

        /** @var BizUser|null $user */
        $user = $this->security->getUser();

        // 这里拿到的是前端的默认分享配置
        $config = $this->config;

        $path = $config['path'];

        if ($user) {
            $value = $this->hashids->encode($user->getId(), Carbon::now()->getTimestamp());

            $parts = parse_url((string) $path);
            $query = $parts['query'] ?? '';
            parse_str($query, $query);
            $query[WechatMiniProgramShareBundle::PARAM_KEY] = $value;
            $path = "{$parts['path']}?" . http_build_query($query);

            $config['path'] = $path;
        }

        return $config;
    }

    protected function getCacheKey(JsonRpcRequest $request): string
    {
        // 如果已经登录了，那我们就不走缓存
        if ($this->security->getUser()) {
            return '';
        }

        return static::buildParamCacheKey($request->getParams());
    }

    protected function getCacheDuration(JsonRpcRequest $request): int
    {
        return 60;
    }

    protected function getCacheTags(JsonRpcRequest $request): iterable
    {
        yield null;
    }
}
