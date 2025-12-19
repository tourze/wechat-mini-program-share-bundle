<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\Param;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use WechatMiniProgramShareBundle\Param\GetWechatMiniProgramPageShareConfigParam;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramPageShareConfigParam::class)]
final class GetWechatMiniProgramPageShareConfigParamTest extends TestCase
{
    public function testParamCanBeConstructed(): void
    {
        $param = new GetWechatMiniProgramPageShareConfigParam();
        $param->path = '/pages/index';
        $param->params = ['id' => '123'];
        $param->config = ['path' => '/pages/share'];

        $this->assertInstanceOf(RpcParamInterface::class, $param);
        $this->assertSame('/pages/index', $param->path);
        $this->assertSame(['id' => '123'], $param->params);
        $this->assertSame(['path' => '/pages/share'], $param->config);
    }
}
