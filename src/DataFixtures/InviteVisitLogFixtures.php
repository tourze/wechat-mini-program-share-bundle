<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;

#[When(env: 'test')]
#[When(env: 'dev')]
class InviteVisitLogFixtures extends Fixture implements FixtureGroupInterface
{
    public const LOG_REFERENCE_PREFIX = 'invite_visit_log_';
    public const LOG_COUNT = 10;

    public static function getGroups(): array
    {
        return ['wechat-mini-program-share'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::LOG_COUNT; ++$i) {
            $log = $this->createInviteVisitLog($i);
            $manager->persist($log);
            $this->addReference(self::LOG_REFERENCE_PREFIX . $i, $log);
        }

        $manager->flush();
    }

    private function createInviteVisitLog(int $index): InviteVisitLog
    {
        $log = new InviteVisitLog();

        $log->setShareOpenId('test_share_openid_' . $index);
        $log->setVisitOpenId('test_visit_openid_' . $index);
        $log->setShareTime(new \DateTimeImmutable('-' . mt_rand(1, 30) . ' days'));
        $log->setVisitTime(new \DateTimeImmutable('-' . mt_rand(1, 30) . ' days'));
        $log->setVisitPath('/pages/index/index?shareId=' . $index);
        $log->setNewUser(1 === mt_rand(0, 1));
        $log->setRegistered(1 === mt_rand(0, 2) ? (bool) mt_rand(0, 1) : null);
        $log->setContext([
            'scene' => mt_rand(1007, 1044),
            'query' => ['shareId' => $index],
            'referrerInfo' => ['appId' => 'test_app_id'],
        ]);
        $log->setCreatedFromIp('192.168.1.' . mt_rand(1, 254));

        return $log;
    }
}
