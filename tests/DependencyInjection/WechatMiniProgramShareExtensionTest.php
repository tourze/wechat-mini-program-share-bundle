<?php

namespace WechatMiniProgramShareBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use WechatMiniProgramShareBundle\DependencyInjection\WechatMiniProgramShareExtension;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;

class WechatMiniProgramShareExtensionTest extends TestCase
{
    private ContainerBuilder $container;
    private WechatMiniProgramShareExtension $extension;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->extension = new WechatMiniProgramShareExtension();
    }

    public function testLoad(): void
    {
        $configs = [];
        $this->container->setParameter('env(HASHID_SALT)', 'test-salt');
        $this->extension->load($configs, $this->container);

        self::assertTrue($this->container->hasDefinition('wechat-mini-program-share.hashids'));
        self::assertTrue($this->container->hasDefinition(ShareCodeRepository::class));
        
        $hashidsDefinition = $this->container->getDefinition('wechat-mini-program-share.hashids');
        self::assertEquals('%env(HASHID_SALT)%', $hashidsDefinition->getArgument(0));
        self::assertEquals(10, $hashidsDefinition->getArgument(1));
    }

    public function testServicesConfiguration(): void
    {
        $configs = [];
        $this->container->setParameter('env(HASHID_SALT)', 'test-salt');
        $this->extension->load($configs, $this->container);

        self::assertTrue($this->container->hasDefinition('wechat-mini-program-share.hashids'));
    }

    public function testRepositoryIsRegistered(): void
    {
        $configs = [];
        $this->container->setParameter('env(HASHID_SALT)', 'test-salt');
        $this->extension->load($configs, $this->container);
        
        self::assertTrue($this->container->hasDefinition(ShareCodeRepository::class));
        // Repository classes are autoconfigured and don't have explicit arguments
    }
}