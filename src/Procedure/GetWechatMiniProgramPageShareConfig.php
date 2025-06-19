<?php

namespace WechatMiniProgramShareBundle\Procedure;

use Carbon\CarbonImmutable;
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

    /**
     * @var array<string, mixed>
     */
    #[MethodParam('当前页面参数')]
    public array $params = [];

    /**
     * @var array<string, mixed>
     */
    #[MethodParam('记录的是默认的分享配置')]
    public array $config = [];

    public function __construct(
        #[Autowire(service: 'wechat-mini-program-share.hashids')] private readonly Hashids $hashids,
        private readonly Security $security,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        if (!isset($this->config['path'])) {
            return [];
        }

        $user = $this->security->getUser();

        // 这里拿到的是前端的默认分享配置
        $config = $this->config;

        $path = $config['path'];

        if ($user !== null) {
            // 使用用户标识符而不是ID，因为UserInterface不保证有getId()方法
            $value = $this->hashids->encode($user->getUserIdentifier(), CarbonImmutable::now()->getTimestamp());

            $pathStr = is_string($path) ? $path : '';
            $parts = parse_url($pathStr);
            if (false === $parts) {
                $parts = [];
            }
            $queryStr = isset($parts['query']) ? $parts['query'] : '';
            parse_str($queryStr, $queryArray);
            $queryArray[WechatMiniProgramShareBundle::PARAM_KEY] = $value;
            $pathPart = isset($parts['path']) ? $parts['path'] : '';
            $path = $pathPart . '?' . http_build_query($queryArray);

            $config['path'] = $path;
        }

        return $config;
    }

    public function getCacheKey(JsonRpcRequest $request): string
    {
        // 如果已经登录了，那我们就不走缓存
        if ($this->security->getUser() !== null) {
            return '';
        }

        return static::buildParamCacheKey($request->getParams());
    }

    public function getCacheDuration(JsonRpcRequest $request): int
    {
        return 60;
    }

    /**
     * @return iterable<string|null>
     */
    public function getCacheTags(JsonRpcRequest $request): iterable
    {
        yield null;
    }
}
