<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Param;

use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;

class GetWechatMiniProgramPageShareConfigParam implements RpcParamInterface
{
    #[MethodParam(description: '当前页面路径')]
    public string $path;

    /**
     * @var array<string, mixed>
     */
    #[MethodParam(description: '当前页面参数')]
    public array $params = [];

    /**
     * @var array<string, mixed>
     */
    #[MethodParam(description: '记录的是默认的分享配置')]
    public array $config = [];
}
