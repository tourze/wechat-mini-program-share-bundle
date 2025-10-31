<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;

/**
 * @extends AbstractCrudController<ShareTicketLog>
 */
#[AdminCrud(routePath: '/wechat-mini-program-share/share-ticket-log', routeName: 'wechat_mini_program_share_share_ticket_log')]
final class ShareTicketLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShareTicketLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('ShareTicket日志')
            ->setEntityLabelInPlural('ShareTicket日志')
            ->setPageTitle('index', 'ShareTicket日志列表')
            ->setPageTitle('detail', '查看ShareTicket日志')
            ->setSearchFields(['openGid'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20)
            ->setTimezone('Asia/Shanghai')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel('查看');
            })
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnIndex();

        yield IntegerField::new('memberId', '用户ID')
            ->setHelp('系统用户ID')
        ;

        yield IntegerField::new('shareMemberId', '分享者用户ID')
            ->setHelp('分享者的系统用户ID')
        ;

        yield TextField::new('openGid', '群标识')
            ->setHelp('微信群的唯一标识符')
        ;

        yield DateTimeField::new('shareTime', '分享时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->setHelp('用户分享到群的时间')
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->onlyOnDetail()
            ->setHelp('记录创建时间')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(NumericFilter::new('memberId', '用户ID'))
            ->add(NumericFilter::new('shareMemberId', '分享者用户ID'))
            ->add(TextFilter::new('openGid', '群标识'))
            ->add(DateTimeFilter::new('shareTime', '分享时间'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
