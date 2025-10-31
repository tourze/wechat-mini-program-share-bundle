<?php

namespace WechatMiniProgramShareBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramPageShareConfig;
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramShareCodeInfo;
use WechatMiniProgramShareBundle\Repository\InviteVisitLogRepository;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;
use WechatMiniProgramShareBundle\Repository\ShareTicketLogRepository;
use WechatMiniProgramShareBundle\Repository\ShareVisitLogRepository;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramShareBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramShareIntegrationTest extends AbstractBundleTestCase
{
    private ContainerBuilder $container;

    protected function onSetUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.debug', true);
        $this->container->setParameter('kernel.environment', 'test');
        $this->container->setParameter('env(HASHID_SALT)', 'test-salt');

        $loader = new YamlFileLoader(
            $this->container,
            new FileLocator(\dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'config')
        );
        $loader->load('services.yaml');
    }

    public function testBundleRegistration(): void
    {
        // Test bundle class exists and has proper namespace
        $bundleClass = self::getBundleClass();
        $this->assertEquals('WechatMiniProgramShareBundle', basename(str_replace('\\', '/', $bundleClass)));
    }

    public function testServicesRegistration(): void
    {
        $this->assertTrue($this->container->hasDefinition(ShareCodeRepository::class));
        $this->assertTrue($this->container->hasDefinition(ShareVisitLogRepository::class));
        $this->assertTrue($this->container->hasDefinition(ShareTicketLogRepository::class));
        $this->assertTrue($this->container->hasDefinition(InviteVisitLogRepository::class));
        // InviteVisitSubscriber 被排除，因为它有复杂的依赖
        $this->assertTrue($this->container->hasDefinition(GetWechatMiniProgramPageShareConfig::class));
        $this->assertTrue($this->container->hasDefinition(GetWechatMiniProgramShareCodeInfo::class));
    }

    public function testHashidsServiceRegistration(): void
    {
        $this->assertTrue($this->container->hasDefinition('wechat-mini-program-share.hashids'));
        $definition = $this->container->getDefinition('wechat-mini-program-share.hashids');
        $this->assertEquals('Hashids\Hashids', $definition->getClass());
        $this->assertNotNull($definition->getFactory());
    }
}
