<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\Param;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use WechatMiniProgramShareBundle\Param\GetWechatMiniProgramShareCodeInfoParam;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramShareCodeInfoParam::class)]
final class GetWechatMiniProgramShareCodeInfoParamTest extends TestCase
{
    public function testParamCanBeConstructed(): void
    {
        $param = new GetWechatMiniProgramShareCodeInfoParam();
        $param->id = 'test-id';
        $param->launchOptions = ['scene' => 'test'];
        $param->enterOptions = ['path' => '/pages/index'];

        $this->assertInstanceOf(RpcParamInterface::class, $param);
        $this->assertSame('test-id', $param->id);
        $this->assertSame(['scene' => 'test'], $param->launchOptions);
        $this->assertSame(['path' => '/pages/index'], $param->enterOptions);
    }
}
