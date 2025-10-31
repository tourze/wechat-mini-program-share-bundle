<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests\Service;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;
use WechatMiniProgramShareBundle\Service\AdminMenu;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    private FactoryInterface $menuFactory;

    protected function onSetUp(): void
    {
        $this->menuFactory = $this->createMock(FactoryInterface::class);
        // 模拟factory创建子菜单项的行为
        $this->menuFactory->method('createItem')->willReturnCallback(function (string $name, array $options = []) {
            return new MenuItem($name, $this->menuFactory);
        });

        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testInvokeCreatesMenu(): void
    {
        $rootMenu = new MenuItem('root', $this->menuFactory);

        $this->adminMenu->__invoke($rootMenu);

        // 验证顶级菜单已创建
        $shareMenu = $rootMenu->getChild('小程序分享管理');
        $this->assertInstanceOf(ItemInterface::class, $shareMenu);
        $this->assertSame('fa fa-share-alt', $shareMenu->getAttribute('icon'));
    }

    public function testInvokeCreatesSubMenuItems(): void
    {
        $rootMenu = new MenuItem('root', $this->menuFactory);

        $this->adminMenu->__invoke($rootMenu);

        $shareMenu = $rootMenu->getChild('小程序分享管理');
        $this->assertNotNull($shareMenu);

        // 验证子菜单项
        $this->assertNotNull($shareMenu->getChild('分享码'));
        $this->assertNotNull($shareMenu->getChild('邀请访问记录'));
        $this->assertNotNull($shareMenu->getChild('分享码访问记录'));
        $this->assertNotNull($shareMenu->getChild('ShareTicket日志'));
    }

    public function testMenuStructureIsCorrect(): void
    {
        $rootMenu = new MenuItem('root', $this->menuFactory);
        $this->adminMenu->__invoke($rootMenu);

        $shareMenu = $rootMenu->getChild('小程序分享管理');
        $this->assertNotNull($shareMenu);

        // 验证所有实体都有对应的菜单项
        $entityMenus = [
            ShareCode::class => '分享码',
            InviteVisitLog::class => '邀请访问记录',
            ShareVisitLog::class => '分享码访问记录',
            ShareTicketLog::class => 'ShareTicket日志',
        ];

        foreach ($entityMenus as $entityClass => $menuLabel) {
            $this->assertNotNull($shareMenu->getChild($menuLabel), "Should have menu item for {$entityClass}");
        }
    }
}
