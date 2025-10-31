<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use WechatMiniProgramShareBundle\Controller\Admin\InviteVisitLogCrudController;
use WechatMiniProgramShareBundle\Controller\Admin\ShareCodeCrudController;
use WechatMiniProgramShareBundle\Controller\Admin\ShareTicketLogCrudController;
use WechatMiniProgramShareBundle\Controller\Admin\ShareVisitLogCrudController;

/**
 * 微信小程序分享Bundle的属性控制器加载器
 */
#[Autoconfigure(public: true)]
readonly class AttributeControllerLoader
{
    /**
     * @return array<string>
     */
    public function getControllers(): array
    {
        return [
            ShareCodeCrudController::class,
            InviteVisitLogCrudController::class,
            ShareVisitLogCrudController::class,
            ShareTicketLogCrudController::class,
        ];
    }
}
