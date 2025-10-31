<?php

namespace WechatMiniProgramShareBundle\Tests\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitSymfonyUnitTest\AbstractEventTestCase;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Event\InviteUserEvent;

/**
 * @internal
 */
#[CoversClass(InviteUserEvent::class)]
final class InviteUserEventTest extends AbstractEventTestCase
{
    public function testGetterSetter(): void
    {
        /**
         * 使用 InviteVisitLog 具体类的原因：
         * 1. 这是针对 InviteUserEvent 的单元测试，需要验证与 InviteVisitLog 类的关联关系
         * 2. InviteVisitLog 类包含了 Doctrine ORM 特有的功能，需要验证完整的实体关联
         * 3. InviteUserEvent 类型声明明确要求 InviteVisitLog 类型，不是通用接口
         */
        $inviteVisitLog = $this->createMock(InviteVisitLog::class);

        $event = new InviteUserEvent();
        $event->setInviteVisitLog($inviteVisitLog);

        $this->assertSame($inviteVisitLog, $event->getInviteVisitLog());
    }

    public function testEventPropagation(): void
    {
        $event = new InviteUserEvent();

        // 验证事件是否可以被停止传播
        $this->assertFalse($event->isPropagationStopped());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
