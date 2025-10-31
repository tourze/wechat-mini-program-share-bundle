<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramShareBundle\Controller\Admin\ShareTicketLogCrudController;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;

/**
 * @internal
 */
#[CoversClass(ShareTicketLogCrudController::class)]
#[RunTestsInSeparateProcesses]
class ShareTicketLogCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerClass(): string
    {
        return ShareTicketLogCrudController::class;
    }

    protected function getEntityClass(): string
    {
        return ShareTicketLog::class;
    }

    protected function getControllerService(): ShareTicketLogCrudController
    {
        return self::getService(ShareTicketLogCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '用户ID' => ['用户ID'];
        yield '分享者用户ID' => ['分享者用户ID'];
        yield '群标识' => ['群标识'];
        yield '分享时间' => ['分享时间'];
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
        $this->assertSame(ShareTicketLog::class, ShareTicketLogCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new ShareTicketLogCrudController();
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
