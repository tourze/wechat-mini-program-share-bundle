<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramShareBundle\Controller\Admin\ShareCodeCrudController;
use WechatMiniProgramShareBundle\Entity\ShareCode;

/**
 * @internal
 */
#[CoversClass(ShareCodeCrudController::class)]
#[RunTestsInSeparateProcesses]
class ShareCodeCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerClass(): string
    {
        return ShareCodeCrudController::class;
    }

    protected function getEntityClass(): string
    {
        return ShareCode::class;
    }

    protected function getControllerService(): ShareCodeCrudController
    {
        return self::getService(ShareCodeCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '小程序账号' => ['小程序账号'];
        yield '链接地址' => ['链接地址'];
        yield '环境版本' => ['环境版本'];
        yield '是否有效' => ['是否有效'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'linkUrl' => ['linkUrl'];
        yield 'imageUrl' => ['imageUrl'];
        // envVersion 是 ChoiceField (select)，valid 是 BooleanField (checkbox)，测试框架只检查 input
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'linkUrl' => ['linkUrl'];
        yield 'imageUrl' => ['imageUrl'];
        // envVersion 是 ChoiceField (select)，valid 是 BooleanField (checkbox)，测试框架只检查 input
    }

    public function testGetEntityFqcn(): void
    {
        $this->assertSame(ShareCode::class, ShareCodeCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new ShareCodeCrudController();
        $fields = $controller->configureFields('index');

        $fieldArray = iterator_to_array($fields);
        $this->assertNotEmpty($fieldArray);
    }
}
