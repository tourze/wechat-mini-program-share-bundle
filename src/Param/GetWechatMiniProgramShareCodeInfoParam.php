<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Param;

use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;

class GetWechatMiniProgramShareCodeInfoParam implements RpcParamInterface
{
    #[MethodParam(description: '分享码ID')]
    public string $id;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $launchOptions = [];

    /**
     * @var array<string, mixed>|null
     */
    public ?array $enterOptions = [];
}
