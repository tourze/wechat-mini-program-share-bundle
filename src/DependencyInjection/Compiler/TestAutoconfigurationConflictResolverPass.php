<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * 测试环境中的自动配置冲突解决器
 *
 * 解决在测试环境中多个Bundle定义相同自动配置标签导致的冲突问题
 */
class TestAutoconfigurationConflictResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // 只在测试环境中处理
        if (!$container->hasParameter('kernel.environment')
            || 'test' !== $container->getParameter('kernel.environment')) {
            return;
        }

        // 清除冲突的自动配置接口
        $autoconfiguredInstanceof = $container->getAutoconfiguredInstanceof();

        $conflictingInterfaces = [
            'Tourze\DoctrineUpsertBundle\Service\ProviderInterface',
            'Tourze\Symfony\CronJob\Provider\CronCommandProvider',
            'Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface',
        ];

        $hasChanges = false;
        foreach ($conflictingInterfaces as $interface) {
            if (isset($autoconfiguredInstanceof[$interface])) {
                unset($autoconfiguredInstanceof[$interface]);
                $hasChanges = true;
            }
        }

        if ($hasChanges) {
            // 使用反射设置autoconfiguredInstanceof属性
            $reflection = new \ReflectionClass($container);
            if ($reflection->hasProperty('autoconfiguredInstanceof')) {
                $property = $reflection->getProperty('autoconfiguredInstanceof');
                $property->setAccessible(true);
                $property->setValue($container, $autoconfiguredInstanceof);
            }
        }
    }
}
