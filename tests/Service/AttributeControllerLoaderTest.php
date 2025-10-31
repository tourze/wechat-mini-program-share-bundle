<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramShareBundle\Service\AttributeControllerLoader;

/**
 * @internal
 */
#[CoversClass(AttributeControllerLoader::class)]
#[RunTestsInSeparateProcesses]
class AttributeControllerLoaderTest extends AbstractIntegrationTestCase
{
    private AttributeControllerLoader $loader;

    protected function onSetUp(): void
    {
        $this->loader = self::getService(AttributeControllerLoader::class);
    }

    public function testGetControllers(): void
    {
        $controllers = $this->loader->getControllers();

        $this->assertIsArray($controllers);
        $this->assertCount(4, $controllers);

        $expectedControllers = [
            'WechatMiniProgramShareBundle\Controller\Admin\ShareCodeCrudController',
            'WechatMiniProgramShareBundle\Controller\Admin\InviteVisitLogCrudController',
            'WechatMiniProgramShareBundle\Controller\Admin\ShareVisitLogCrudController',
            'WechatMiniProgramShareBundle\Controller\Admin\ShareTicketLogCrudController',
        ];

        foreach ($expectedControllers as $expectedController) {
            $this->assertContains($expectedController, $controllers);
        }
    }

    public function testControllersCanBeInstantiated(): void
    {
        $controllers = $this->loader->getControllers();

        foreach ($controllers as $controllerClass) {
            // 验证控制器可以从容器中获取（如果需要依赖注入）
            if (self::getContainer()->has($controllerClass)) {
                $instance = self::getContainer()->get($controllerClass);
                $this->assertIsObject($instance);
                $this->assertSame($controllerClass, get_class($instance));
            } else {
                // 如果不在容器中，验证类的基本信息
                /** @var class-string $controllerClass */
                $reflection = new \ReflectionClass($controllerClass);
                $this->assertTrue($reflection->isInstantiable(), "Controller should be instantiable: {$controllerClass}");
                $this->assertNotEmpty($reflection->getMethods(), "Controller should have methods: {$controllerClass}");

                // 验证控制器继承正确的基类或实现正确的接口
                $this->assertTrue(
                    $reflection->isSubclassOf('EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController'),
                    "Controller should extend AbstractCrudController: {$controllerClass}"
                );
            }
        }
    }
}
