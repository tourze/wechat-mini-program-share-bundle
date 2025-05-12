<?php

namespace WechatMiniProgramShareBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Event\InviteUserEvent;

class InviteUserEventTest extends TestCase
{
    public function testGetterSetter(): void
    {
        $inviteVisitLog = $this->createMock(InviteVisitLog::class);
        
        $event = new InviteUserEvent();
        $event->setInviteVisitLog($inviteVisitLog);
        
        $this->assertSame($inviteVisitLog, $event->getInviteVisitLog());
    }
    
    public function testInheritance(): void
    {
        $event = new InviteUserEvent();
        $this->assertInstanceOf('Symfony\Contracts\EventDispatcher\Event', $event);
    }
}