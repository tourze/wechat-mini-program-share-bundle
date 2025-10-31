<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatMiniProgramShareBundle\DependencyInjection\Compiler\TestAutoconfigurationConflictResolverPass;

use function PHPUnit\Framework\callback;

/**
 * 测试自动配置冲突解决器
 * @internal
 */
#[CoversClass(TestAutoconfigurationConflictResolverPass::class)]
class TestAutoconfigurationConflictResolverPassTest extends TestCase
{
    public function testProcessInTestEnvironment(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())
            ->method('hasParameter')
            ->with('kernel.environment')
            ->willReturn(true)
        ;

        $container->expects($this->once())
            ->method('getParameter')
            ->with('kernel.environment')
            ->willReturn('test')
        ;

        // 模拟自动配置实例存在
        $autoconfiguredInstanceof = [
            'Tourze\DoctrineUpsertBundle\Service\ProviderInterface' => 'some_definition',
            'Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface' => 'some_definition',
            'Some\Other\Interface' => 'some_definition',
        ];

        $container->expects($this->once())
            ->method('getAutoconfiguredInstanceof')
            ->willReturn($autoconfiguredInstanceof)
        ;

        // 使用反射来验证设置
        $container->expects($this->atLeastOnce())
            ->method('getAutoconfiguredInstanceof')
            ->willReturnOnConsecutiveCalls(
                $autoconfiguredInstanceof,
                callback(function ($value) {
                    // 验证冲突接口已被移除
                    $this->assertIsArray($value, 'Expected array for autoconfigured instanceof configuration');
                    $this->assertArrayNotHasKey('Tourze\DoctrineUpsertBundle\Service\ProviderInterface', $value);
                    $this->assertArrayNotHasKey('Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface', $value);
                    $this->assertArrayHasKey('Some\Other\Interface', $value); // 非冲突接口应该保留

                    return true;
                })
            )
        ;

        $pass = new TestAutoconfigurationConflictResolverPass();
        $pass->process($container);
    }

    public function testProcessInNonTestEnvironment(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())
            ->method('hasParameter')
            ->with('kernel.environment')
            ->willReturn(true)
        ;

        $container->expects($this->once())
            ->method('getParameter')
            ->with('kernel.environment')
            ->willReturn('prod')
        ;

        $container->expects($this->never())
            ->method('getAutoconfiguredInstanceof')
        ;

        $pass = new TestAutoconfigurationConflictResolverPass();
        $pass->process($container);
    }

    public function testProcessWithoutEnvironmentParameter(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())
            ->method('hasParameter')
            ->with('kernel.environment')
            ->willReturn(false)
        ;

        $container->expects($this->never())
            ->method('getParameter')
        ;

        $container->expects($this->never())
            ->method('getAutoconfiguredInstanceof')
        ;

        $pass = new TestAutoconfigurationConflictResolverPass();
        $pass->process($container);
    }
}
