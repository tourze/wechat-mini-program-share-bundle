<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;

/**
 * @extends AbstractCrudController<InviteVisitLog>
 */
#[AdminCrud(routePath: '/wechat-mini-program-share/invite-visit-log', routeName: 'wechat_mini_program_share_invite_visit_log')]
final class InviteVisitLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InviteVisitLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('邀请访问记录')
            ->setEntityLabelInPlural('邀请访问记录')
            ->setPageTitle('index', '邀请访问记录列表')
            ->setPageTitle('detail', '查看邀请访问记录')
            ->setPageTitle('new', '新建邀请访问记录')
            ->setPageTitle('edit', '编辑邀请访问记录')
            ->setSearchFields(['shareOpenId', 'visitOpenId', 'visitPath'])
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

        yield TextField::new('shareOpenId', '分享者OpenID')
            ->setHelp('微信用户的OpenID标识')
        ;

        yield AssociationField::new('shareUser', '分享者用户')
            ->setHelp('系统中对应的用户账号')
            ->hideOnIndex()
        ;

        yield DateTimeField::new('shareTime', '分享时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->setHelp('用户分享的时间')
        ;

        yield TextField::new('visitOpenId', '访问者OpenID')
            ->setHelp('访问者的微信OpenID')
        ;

        yield AssociationField::new('visitUser', '访问者用户')
            ->setHelp('系统中对应的访问者账号')
            ->hideOnIndex()
        ;

        yield DateTimeField::new('visitTime', '受邀时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->setHelp('用户通过分享链接访问的时间')
        ;

        yield TextField::new('visitPath', '访问地址')
            ->setHelp('用户访问的页面路径')
            ->hideOnIndex()
        ;

        yield BooleanField::new('newUser', '是否新用户')
            ->setHelp('访问者是否为新用户')
        ;

        yield BooleanField::new('registered', '是否已注册')
            ->setHelp('访问者是否已完成注册')
            ->hideOnIndex()
        ;

        yield TextField::new('createdFromIp', '访问IP')
            ->onlyOnDetail()
            ->setHelp('访问者的IP地址')
        ;

        yield ArrayField::new('context', '上下文信息')
            ->onlyOnDetail()
            ->setHelp('额外的上下文数据')
        ;

        yield ArrayField::new('launchOptions', '启动参数')
            ->onlyOnDetail()
            ->setHelp('小程序启动时的参数')
        ;

        yield ArrayField::new('enterOptions', '热启动参数')
            ->onlyOnDetail()
            ->setHelp('小程序热启动时的参数')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('shareOpenId', '分享者OpenID'))
            ->add(TextFilter::new('visitOpenId', '访问者OpenID'))
            ->add(BooleanFilter::new('newUser', '是否新用户'))
            ->add(BooleanFilter::new('registered', '是否已注册'))
            ->add(DateTimeFilter::new('shareTime', '分享时间'))
            ->add(DateTimeFilter::new('visitTime', '受邀时间'))
        ;
    }
}
