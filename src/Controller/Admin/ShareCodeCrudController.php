<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareCode;

/**
 * @extends AbstractCrudController<ShareCode>
 */
#[AdminCrud(routePath: '/wechat-mini-program-share/share-code', routeName: 'wechat_mini_program_share_share_code')]
final class ShareCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShareCode::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('分享码')
            ->setEntityLabelInPlural('分享码')
            ->setPageTitle('index', '分享码列表')
            ->setPageTitle('detail', '查看分享码')
            ->setPageTitle('new', '新建分享码')
            ->setPageTitle('edit', '编辑分享码')
            ->setSearchFields(['linkUrl', 'imageUrl'])
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

        yield AssociationField::new('account', '小程序账号')
            ->setHelp('关联的微信小程序账号')
        ;

        yield UrlField::new('linkUrl', '链接地址')
            ->setHelp('分享码对应的链接地址')
        ;

        yield UrlField::new('imageUrl', '图片地址')
            ->setHelp('分享码的图片地址')
            ->hideOnIndex()
        ;

        yield ChoiceField::new('envVersion', '环境版本')
            ->setChoices([
                '开发版' => EnvVersion::DEVELOP,
                '体验版' => EnvVersion::TRIAL,
                '正式版' => EnvVersion::RELEASE,
            ])
            ->setHelp('小程序的环境版本')
        ;

        yield BooleanField::new('valid', '是否有效')
            ->setHelp('分享码是否有效')
        ;

        yield AssociationField::new('user', '创建用户')
            ->setHelp('创建此分享码的用户')
            ->hideOnIndex()
        ;

        yield IntegerField::new('size', '大小')
            ->setHelp('分享码的大小（字节）')
            ->hideOnIndex()
        ;

        yield TextField::new('createdFromIp', '创建IP')
            ->onlyOnDetail()
            ->setHelp('创建分享码时的IP地址')
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->onlyOnDetail()
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->onlyOnDetail()
        ;

        yield TextField::new('createdBy', '创建者')
            ->onlyOnDetail()
            ->setHelp('创建此记录的用户标识')
        ;

        yield TextField::new('updatedBy', '更新者')
            ->onlyOnDetail()
            ->setHelp('最后更新此记录的用户标识')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('valid', '是否有效'))
            ->add(ChoiceFilter::new('envVersion', '环境版本')
                ->setChoices([
                    '开发版' => EnvVersion::DEVELOP,
                    '体验版' => EnvVersion::TRIAL,
                    '正式版' => EnvVersion::RELEASE,
                ]))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
            ->add(DateTimeFilter::new('updateTime', '更新时间'))
        ;
    }
}
