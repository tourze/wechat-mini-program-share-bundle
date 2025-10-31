<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;

#[When(env: 'test')]
#[When(env: 'dev')]
class ShareTicketLogFixtures extends Fixture implements FixtureGroupInterface
{
    public const TICKET_REFERENCE_PREFIX = 'share_ticket_log_';
    public const TICKET_COUNT = 20;

    public static function getGroups(): array
    {
        return ['wechat-mini-program-share'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::TICKET_COUNT; ++$i) {
            $log = $this->createShareTicketLog($i);
            $manager->persist($log);
            $this->addReference(self::TICKET_REFERENCE_PREFIX . $i, $log);
        }

        $manager->flush();
    }

    private function createShareTicketLog(int $index): ShareTicketLog
    {
        $log = new ShareTicketLog();

        $log->setMemberId(mt_rand(1000, 9999));
        $log->setShareMemberId(mt_rand(1000, 9999));
        $log->setOpenGid('test_group_' . $index . '_' . uniqid());
        $log->setCreateTime(new \DateTimeImmutable('-' . mt_rand(1, 30) . ' days'));
        $log->setShareTime(new \DateTimeImmutable('-' . mt_rand(1, 30) . ' days'));

        return $log;
    }
}
