<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramShareBundle\Controller\Admin\ShareVisitLogCrudController;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

/**
 * @internal
 */
#[CoversClass(ShareVisitLogCrudController::class)]
#[RunTestsInSeparateProcesses]
class ShareVisitLogCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerClass(): string
    {
        return ShareVisitLogCrudController::class;
    }

    protected function getEntityClass(): string
    {
        return ShareVisitLog::class;
    }

    protected function getControllerService(): ShareVisitLogCrudController
    {
        return self::getService(ShareVisitLogCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '分享码' => ['分享码'];
        yield '环境版本' => ['环境版本'];
        yield '访问IP' => ['访问IP'];
        yield '访问时间' => ['访问时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        // EDIT action is disabled for log controllers - return minimal data
        yield 'dummy' => ['dummy'];
    }

    public function testGetEntityFqcn(): void
    {
        $this->assertSame(ShareVisitLog::class, ShareVisitLogCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new ShareVisitLogCrudController();
        $fields = $controller->configureFields('index');

        $fieldArray = iterator_to_array($fields);
        $this->assertNotEmpty($fieldArray);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        // NEW action is disabled for log controllers - return minimal data
        yield 'dummy' => ['dummy'];
    }
}
