<?php

namespace WechatMiniProgramShareBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;

class InviteUserEvent extends Event
{
    private InviteVisitLog $inviteVisitLog;

    public function getInviteVisitLog(): InviteVisitLog
    {
        return $this->inviteVisitLog;
    }

    public function setInviteVisitLog(InviteVisitLog $inviteVisitLog): void
    {
        $this->inviteVisitLog = $inviteVisitLog;
    }
}
