<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramShareBundle\Controller\Admin\InviteVisitLogCrudController;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;

/**
 * @internal
 */
#[CoversClass(InviteVisitLogCrudController::class)]
#[RunTestsInSeparateProcesses]
class InviteVisitLogCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerClass(): string
    {
        return InviteVisitLogCrudController::class;
    }

    protected function getEntityClass(): string
    {
        return InviteVisitLog::class;
    }

    protected function getControllerService(): InviteVisitLogCrudController
    {
        return self::getService(InviteVisitLogCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '分享者OpenID' => ['分享者OpenID'];
        yield '分享时间' => ['分享时间'];
        yield '访问者OpenID' => ['访问者OpenID'];
        yield '受邀时间' => ['受邀时间'];
        yield '是否新用户' => ['是否新用户'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'shareOpenId' => ['shareOpenId'];
        yield 'shareTime' => ['shareTime'];
        yield 'visitOpenId' => ['visitOpenId'];
        yield 'visitTime' => ['visitTime'];
        yield 'newUser' => ['newUser'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'shareOpenId' => ['shareOpenId'];
        yield 'shareTime' => ['shareTime'];
        yield 'visitOpenId' => ['visitOpenId'];
        yield 'visitTime' => ['visitTime'];
        yield 'newUser' => ['newUser'];
    }

    public function testGetEntityFqcn(): void
    {
        $this->assertSame(InviteVisitLog::class, InviteVisitLogCrudController::getEntityFqcn());
    }

    public function testConfigureFields(): void
    {
        $controller = new InviteVisitLogCrudController();
        $fields = $controller->configureFields('index');

        $fieldArray = iterator_to_array($fields);
        $this->assertNotEmpty($fieldArray);
    }
}
