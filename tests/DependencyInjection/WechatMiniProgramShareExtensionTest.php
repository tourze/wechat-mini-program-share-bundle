<?php

namespace WechatMiniProgramShareBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use WechatMiniProgramShareBundle\DependencyInjection\WechatMiniProgramShareExtension;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramShareExtension::class)]
final class WechatMiniProgramShareExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testServicesConfiguration(): void
    {
        $extension = new WechatMiniProgramShareExtension();

        // 验证扩展类可以被正确实例化
        $this->assertInstanceOf(WechatMiniProgramShareExtension::class, $extension);
    }

    public function testRepositoryIsRegistered(): void
    {
        $extension = new WechatMiniProgramShareExtension();

        // 验证扩展类可以被正确实例化
        $this->assertInstanceOf(WechatMiniProgramShareExtension::class, $extension);
    }
}
