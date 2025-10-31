# WeChat Mini Program Share Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Build Status](https://github.com/tourze/php-monorepo/actions/workflows/ci.yml/badge.svg)](https://github.com/tourze/php-monorepo/actions)
[![Code Coverage](https://codecov.io/gh/tourze/php-monorepo/branch/master/graph/badge.svg)](https://codecov.io/gh/tourze/php-monorepo)
[![PHP Version Require](https://img.shields.io/badge/php-%3E%3D8.1-blue)](https://packagist.org/packages/tourze/wechat-mini-program-share-bundle)  
[![License](https://img.shields.io/badge/license-MIT-green)](https://github.com/tourze/wechat-mini-program-share-bundle/blob/master/LICENSE)
[![Symfony](https://img.shields.io/badge/symfony-%3E%3D7.3-purple)](https://symfony.com/)

A Symfony bundle for handling WeChat Mini Program sharing functionality, including share code generation, visit tracking, and invitation management.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
  - [1. Generate Share Configuration](#1-generate-share-configuration)
  - [2. Handle Share Code Information](#2-handle-share-code-information)
  - [3. Track Invitations](#3-track-invitations)
- [Entities](#entities)
- [Events](#events)
- [Advanced Usage](#advanced-usage)
  - [Custom Event Listeners](#custom-event-listeners)
  - [Custom Share Code Validation](#custom-share-code-validation)
  - [Extending Share Analytics](#extending-share-analytics)
- [Requirements](#requirements)
- [License](#license)

## Features

- **Share Code Management**: Generate and manage mini program share codes
- **Visit Tracking**: Track user visits through shared links
- **Invitation System**: Handle user invitations and track referral relationships
- **Analytics**: Comprehensive logging and analytics for sharing behavior
- **Event-Driven**: Symfony event system integration for extensibility

## Installation

```bash
composer require tourze/wechat-mini-program-share-bundle
```

## Configuration

Add the bundle to your `bundles.php`:

```php
return [
    // ...
    WechatMiniProgramShareBundle\WechatMiniProgramShareBundle::class => ['all' => true],
];
```

## Quick Start

### 1. Generate Share Configuration

Use the `GetWechatMiniProgramPageShareConfig` procedure to generate share paths with tracking parameters:

```php
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramPageShareConfig;

$procedure = new GetWechatMiniProgramPageShareConfig($hashids, $security);
$procedure->path = '/pages/product/detail';
$procedure->params = ['id' => 123];
$procedure->config = [
    'title' => 'Product Title',
    'path' => '/pages/product/detail?id=123'
];

$shareConfig = $procedure->execute();
```

### 2. Handle Share Code Information

Use the `GetWechatMiniProgramShareCodeInfo` procedure to process share codes:

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
// Returns redirect information for the mini program
```

### 3. Track Invitations

The bundle automatically tracks user invitations through the `InviteVisitSubscriber`:

```php
// The subscriber listens to CodeToSessionResponseEvent
// and automatically creates InviteVisitLog entries
```

## Entities

### ShareCode
Represents a share code with validation and tracking information.

### ShareVisitLog
Tracks visits through share codes.

### InviteVisitLog
Records invitation relationships between users.

### ShareTicketLog
Logs share ticket operations.

## Events

### InviteUserEvent
Dispatched when a user is invited through sharing.

```php
use WechatMiniProgramShareBundle\Event\InviteUserEvent;

// Listen to the event
public function onInviteUser(InviteUserEvent $event): void
{
    $log = $event->getInviteVisitLog();
    // Handle invitation logic
}
```

## Advanced Usage

### Custom Event Listeners

You can create custom event listeners to extend the sharing functionality:

```php
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use WechatMiniProgramShareBundle\Event\InviteUserEvent;

#[AsEventListener]
class CustomInviteListener
{
    public function onInviteUser(InviteUserEvent $event): void
    {
        $log = $event->getInviteVisitLog();
        
        // Custom logic for handling invitations
        if ($log->isNewUser()) {
            // Send welcome message
            $this->sendWelcomeMessage($log->getVisitUser());
        }
        
        // Award points to the inviter
        $this->awardInvitationPoints($log->getShareUser());
    }
}
```

## Custom Share Code Validation

Implement custom validation logic for share codes:

```php
use WechatMiniProgramShareBundle\Entity\ShareCode;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CustomShareCodeValidator
{
    public function validate(ShareCode $shareCode, ExecutionContextInterface $context): void
    {
        if (!$this->isValidDomain($shareCode->getLinkUrl())) {
            $context->buildViolation('Invalid domain for share URL')
                ->atPath('linkUrl')
                ->addViolation();
        }
    }
}
```

### Extending Share Analytics

Create custom analytics by listening to share events:

```php
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;
use Doctrine\ORM\Event\PostPersistEventArgs;

class ShareAnalyticsListener
{
    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if ($entity instanceof ShareVisitLog) {
            // Send analytics data to external service
            $this->analyticsService->track('share_visit', [
                'code_id' => $entity->getCode()->getId(),
                'user_id' => $entity->getUser()?->getId(),
                'timestamp' => $entity->getCreateTime(),
            ]);
        }
    }
}
```

## Requirements

- PHP 8.1+
- Symfony 7.3+
- Doctrine ORM 3.0+
- WeChat Mini Program Bundle (tourze/wechat-mini-program-bundle)
- WeChat Mini Program Auth Bundle (tourze/wechat-mini-program-auth-bundle)
- Hashids PHP 5.0+

## License

MIT