<?php

namespace WechatMiniProgramShareBundle\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use WechatMiniProgramShareBundle\EventSubscriber\InviteVisitSubscriber;
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramPageShareConfig;
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramShareCodeInfo;
use WechatMiniProgramShareBundle\Repository\InviteVisitLogRepository;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;
use WechatMiniProgramShareBundle\Repository\ShareTicketLogRepository;
use WechatMiniProgramShareBundle\Repository\ShareVisitLogRepository;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

class WechatMiniProgramShareIntegrationTest extends TestCase
{
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.debug', true);
        $this->container->setParameter('kernel.environment', 'test');
        $this->container->setParameter('env(HASHID_SALT)', 'test-salt');

        $bundle = new WechatMiniProgramShareBundle();
        $bundle->build($this->container);

        $loader = new YamlFileLoader(
            $this->container,
            new FileLocator(__DIR__ . '/../../src/Resources/config')
        );
        $loader->load('services.yaml');
    }

    public function testBundleRegistration(): void
    {
        $bundle = new WechatMiniProgramShareBundle();
        $this->assertInstanceOf(WechatMiniProgramShareBundle::class, $bundle);
    }

    public function testServicesRegistration(): void
    {
        $this->assertTrue($this->container->hasDefinition(ShareCodeRepository::class));
        $this->assertTrue($this->container->hasDefinition(ShareVisitLogRepository::class));
        $this->assertTrue($this->container->hasDefinition(ShareTicketLogRepository::class));
        $this->assertTrue($this->container->hasDefinition(InviteVisitLogRepository::class));
        $this->assertTrue($this->container->hasDefinition(InviteVisitSubscriber::class));
        $this->assertTrue($this->container->hasDefinition(GetWechatMiniProgramPageShareConfig::class));
        $this->assertTrue($this->container->hasDefinition(GetWechatMiniProgramShareCodeInfo::class));
    }

    public function testHashidsServiceRegistration(): void
    {
        $this->assertTrue($this->container->hasDefinition('wechat-mini-program-share.hashids'));
        $definition = $this->container->getDefinition('wechat-mini-program-share.hashids');
        $this->assertTrue($definition->isPublic());
        $this->assertEquals('Hashids\Hashids', $definition->getClass());
    }
}