<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

/**
 * 微信小程序分享Bundle管理菜单服务
 *
 * 为EasyAdmin提供微信小程序分享相关的管理菜单配置
 */
#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        // 创建小程序分享顶级菜单
        if (null === $item->getChild('小程序分享管理')) {
            $item->addChild('小程序分享管理')
                ->setAttribute('icon', 'fa fa-share-alt')
            ;
        }

        $shareMenu = $item->getChild('小程序分享管理');
        if (null === $shareMenu) {
            return;
        }

        // 分享码管理
        $shareMenu
            ->addChild('分享码')
            ->setUri($this->linkGenerator->getCurdListPage(ShareCode::class))
            ->setAttribute('icon', 'fa fa-qrcode')
        ;

        // 邀请访问记录
        $shareMenu
            ->addChild('邀请访问记录')
            ->setUri($this->linkGenerator->getCurdListPage(InviteVisitLog::class))
            ->setAttribute('icon', 'fa fa-user-friends')
        ;

        // 分享码访问记录
        $shareMenu
            ->addChild('分享码访问记录')
            ->setUri($this->linkGenerator->getCurdListPage(ShareVisitLog::class))
            ->setAttribute('icon', 'fa fa-eye')
        ;

        // ShareTicket日志
        $shareMenu
            ->addChild('ShareTicket日志')
            ->setUri($this->linkGenerator->getCurdListPage(ShareTicketLog::class))
            ->setAttribute('icon', 'fa fa-users')
        ;
    }
}
