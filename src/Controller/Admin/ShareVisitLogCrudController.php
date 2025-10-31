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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

/**
 * @extends AbstractCrudController<ShareVisitLog>
 */
#[AdminCrud(routePath: '/wechat-mini-program-share/share-visit-log', routeName: 'wechat_mini_program_share_share_visit_log')]
final class ShareVisitLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShareVisitLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('分享码访问记录')
            ->setEntityLabelInPlural('分享码访问记录')
            ->setPageTitle('index', '分享码访问记录列表')
            ->setPageTitle('detail', '查看分享码访问记录')
            ->setSearchFields(['createdFromIp'])
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

        yield AssociationField::new('code', '分享码')
            ->setHelp('被访问的分享码')
        ;

        yield ChoiceField::new('envVersion', '环境版本')
            ->setChoices([
                '开发版' => EnvVersion::DEVELOP,
                '体验版' => EnvVersion::TRIAL,
                '正式版' => EnvVersion::RELEASE,
            ])
            ->setHelp('访问时的小程序环境版本')
        ;

        yield AssociationField::new('user', '访问用户')
            ->setHelp('访问分享码的用户')
            ->hideOnIndex()
        ;

        yield TextField::new('createdFromIp', '访问IP')
            ->setHelp('访问者的IP地址')
        ;

        yield DateTimeField::new('createTime', '访问时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->setHelp('访问分享码的时间')
        ;

        yield ArrayField::new('response', '响应数据')
            ->onlyOnDetail()
            ->setHelp('访问时的响应数据（JSON格式）')
        ;

        yield ArrayField::new('launchOptions', '启动参数')
            ->onlyOnDetail()
            ->setHelp('小程序启动时的参数')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('code', '分享码'))
            ->add(ChoiceFilter::new('envVersion', '环境版本')
                ->setChoices([
                    '开发版' => EnvVersion::DEVELOP,
                    '体验版' => EnvVersion::TRIAL,
                    '正式版' => EnvVersion::RELEASE,
                ]))
            ->add(DateTimeFilter::new('createTime', '访问时间'))
        ;
    }
}
