# 微信小程序分享 Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Build Status](https://github.com/tourze/php-monorepo/actions/workflows/ci.yml/badge.svg)](https://github.com/tourze/php-monorepo/actions)
[![Code Coverage](https://codecov.io/gh/tourze/php-monorepo/branch/master/graph/badge.svg)](https://codecov.io/gh/tourze/php-monorepo)
[![PHP Version Require](https://img.shields.io/badge/php-%3E%3D8.1-blue)](https://packagist.org/packages/tourze/wechat-mini-program-share-bundle)  
[![License](https://img.shields.io/badge/license-MIT-green)](https://github.com/tourze/wechat-mini-program-share-bundle/blob/master/LICENSE)
[![Symfony](https://img.shields.io/badge/symfony-%3E%3D7.3-purple)](https://symfony.com/)

一个用于处理微信小程序分享功能的 Symfony Bundle，包括分享码生成、访问跟踪和邀请管理等功能。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [配置](#配置)
- [快速开始](#快速开始)
  - [1. 生成分享配置](#1-生成分享配置)
  - [2. 处理分享码信息](#2-处理分享码信息)
  - [3. 跟踪邀请](#3-跟踪邀请)
- [实体](#实体)
- [事件](#事件)
- [高级用法](#高级用法)
  - [自定义事件监听器](#自定义事件监听器)
  - [自定义分享码验证](#自定义分享码验证)
  - [扩展分享分析](#扩展分享分析)
- [系统要求](#系统要求)
- [许可证](#许可证)

## 功能特性

- **分享码管理**: 生成和管理小程序分享码
- **访问跟踪**: 跟踪用户通过分享链接的访问记录
- **邀请系统**: 处理用户邀请并跟踪推荐关系
- **数据分析**: 全面的分享行为日志记录和分析
- **事件驱动**: 集成 Symfony 事件系统，具有良好的扩展性

## 安装

```bash
composer require tourze/wechat-mini-program-share-bundle
```

## 配置

在 `bundles.php` 中添加 Bundle：

```php
return [
    // ...
    WechatMiniProgramShareBundle\WechatMiniProgramShareBundle::class => ['all' => true],
];
```

## 快速开始

### 1. 生成分享配置

使用 `GetWechatMiniProgramPageShareConfig` 过程生成带有跟踪参数的分享路径：

```php
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramPageShareConfig;

$procedure = new GetWechatMiniProgramPageShareConfig($hashids, $security);
$procedure->path = '/pages/product/detail';
$procedure->params = ['id' => 123];
$procedure->config = [
    'title' => '产品标题',
    'path' => '/pages/product/detail?id=123'
];

$shareConfig = $procedure->execute();
```

### 2. 处理分享码信息

使用 `GetWechatMiniProgramShareCodeInfo` 过程处理分享码：

```php
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramShareCodeInfo;

$procedure = new GetWechatMiniProgramShareCodeInfo(
    $codeRepository,
    $doctrineService,
    $security,
    $logger
);
$procedure->id = 'share-code-id';

$result = $procedure->execute();
// 返回小程序的重定向信息
```

### 3. 跟踪邀请

Bundle 通过 `InviteVisitSubscriber` 自动跟踪用户邀请：

```php
// 订阅者监听 CodeToSessionResponseEvent 事件
// 并自动创建 InviteVisitLog 条目
```

## 实体

### ShareCode
表示分享码，包含验证和跟踪信息。

### ShareVisitLog
跟踪通过分享码的访问记录。

### InviteVisitLog
记录用户之间的邀请关系。

### ShareTicketLog
记录分享票据操作。

## 事件

### InviteUserEvent
当用户通过分享被邀请时触发。

```php
use WechatMiniProgramShareBundle\Event\InviteUserEvent;

// 监听事件
public function onInviteUser(InviteUserEvent $event): void
{
    $log = $event->getInviteVisitLog();
    // 处理邀请逻辑
}
```

## 高级用法

### 自定义事件监听器

您可以创建自定义事件监听器来扩展分享功能：

```php
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use WechatMiniProgramShareBundle\Event\InviteUserEvent;

#[AsEventListener]
class CustomInviteListener
{
    public function onInviteUser(InviteUserEvent $event): void
    {
        $log = $event->getInviteVisitLog();
        
        // 处理邀请的自定义逻辑
        if ($log->isNewUser()) {
            // 发送欢迎消息
            $this->sendWelcomeMessage($log->getVisitUser());
        }
        
        // 给邀请人奖励积分
        $this->awardInvitationPoints($log->getShareUser());
    }
}
```

## 自定义分享码验证

为分享码实现自定义验证逻辑：

```php
use WechatMiniProgramShareBundle\Entity\ShareCode;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CustomShareCodeValidator
{
    public function validate(ShareCode $shareCode, ExecutionContextInterface $context): void
    {
        if (!$this->isValidDomain($shareCode->getLinkUrl())) {
            $context->buildViolation('分享链接的域名无效')
                ->atPath('linkUrl')
                ->addViolation();
        }
    }
}
```

### 扩展分享分析

通过监听分享事件创建自定义分析：

```php
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;
use Doctrine\ORM\Event\PostPersistEventArgs;

class ShareAnalyticsListener
{
    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if ($entity instanceof ShareVisitLog) {
            // 向外部服务发送分析数据
            $this->analyticsService->track('share_visit', [
                'code_id' => $entity->getCode()->getId(),
                'user_id' => $entity->getUser()?->getId(),
                'timestamp' => $entity->getCreateTime(),
            ]);
        }
    }
}
```

## 系统要求

- PHP 8.1+
- Symfony 7.3+
- Doctrine ORM 3.0+
- 微信小程序 Bundle (tourze/wechat-mini-program-bundle)
- 微信小程序认证 Bundle (tourze/wechat-mini-program-auth-bundle)
- Hashids PHP 5.0+

## 许可证

MIT
