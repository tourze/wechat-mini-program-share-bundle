<?php

namespace WechatMiniProgramShareBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineAsyncInsertBundle\DoctrineAsyncInsertBundle;
use WechatMiniProgramAuthBundle\WechatMiniProgramAuthBundle;
use WechatMiniProgramBundle\WechatMiniProgramBundle;
use WechatMiniProgramShareBundle\DependencyInjection\Compiler\TestAutoconfigurationConflictResolverPass;

class WechatMiniProgramShareBundle extends Bundle implements BundleDependencyInterface
{
    final public const PARAM_KEY = '_shareFrom';

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        // 添加测试环境自动配置冲突解决器
        $container->addCompilerPass(new TestAutoconfigurationConflictResolverPass());
    }

    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            SecurityBundle::class => ['all' => true],
            DoctrineAsyncInsertBundle::class => ['all' => true],
            WechatMiniProgramAuthBundle::class => ['all' => true],
            WechatMiniProgramBundle::class => ['all' => true],
        ];
    }
}
