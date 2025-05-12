<?php

namespace WechatMiniProgramShareBundle\Tests\Integration;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

class IntegrationTestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new SecurityBundle(),
            new WechatMiniProgramShareBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', [
                'test' => true,
                'router' => [
                    'utf8' => true,
                    'resource' => 'kernel::loadRoutes',
                ],
                'secret' => 'test',
            ]);
            
            $container->loadFromExtension('doctrine', [
                'dbal' => [
                    'driver' => 'pdo_sqlite',
                    'url' => 'sqlite:///:memory:',
                ],
                'orm' => [
                    'auto_generate_proxy_classes' => true,
                    'entity_managers' => [
                        'default' => [
                            'auto_mapping' => true,
                            'mappings' => [
                                'WechatMiniProgramShareBundle' => [
                                    'is_bundle' => true,
                                    'type' => 'attribute',
                                    'dir' => 'Entity',
                                    'prefix' => 'WechatMiniProgramShareBundle\Entity',
                                    'alias' => 'WechatMiniProgramShareBundle',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
            
            $container->loadFromExtension('security', [
                'providers' => [
                    'test_provider' => [
                        'memory' => [
                            'users' => [],
                        ],
                    ],
                ],
                'firewalls' => [
                    'main' => [
                        'provider' => 'test_provider',
                        'http_basic' => null,
                    ],
                ],
            ]);
            
            // 设置环境变量
            $container->setParameter('env(HASHID_SALT)', 'test_salt_for_hashids');
            $container->setParameter('env(WECHAT_MINI_PROGRAM_INDEX_PAGE)', '/pages/index/index');
        });
    }
    
    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        // 空路由配置，仅为满足框架要求
    }
    
    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/cache/'.spl_object_hash($this);
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/logs/'.spl_object_hash($this);
    }
}