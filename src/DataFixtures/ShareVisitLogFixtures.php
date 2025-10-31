<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

#[When(env: 'test')]
#[When(env: 'dev')]
class ShareVisitLogFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const VISIT_REFERENCE_PREFIX = 'share_visit_log_';
    public const VISIT_COUNT = 25;

    public static function getGroups(): array
    {
        return ['wechat-mini-program-share'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::VISIT_COUNT; ++$i) {
            $log = $this->createShareVisitLog($i);
            $manager->persist($log);
            $this->addReference(self::VISIT_REFERENCE_PREFIX . $i, $log);
        }

        $manager->flush();
    }

    private function createShareVisitLog(int $index): ShareVisitLog
    {
        $log = new ShareVisitLog();

        $codeIndex = $index % ShareCodeFixtures::CODE_COUNT;
        $shareCode = $this->getReference(ShareCodeFixtures::CODE_REFERENCE_PREFIX . $codeIndex, ShareCode::class);

        $log->setCode($shareCode);
        $log->setEnvVersion($this->getRandomEnvVersion());
        $log->setResponse([
            'success' => true,
            'timestamp' => time(),
            'visitId' => $index,
            'userAgent' => 'MicroMessenger/8.0.' . mt_rand(1, 30) . ' (iOS)',
        ]);
        $log->setCreateTime(new \DateTimeImmutable('-' . mt_rand(1, 15) . ' days'));
        $log->setCreatedFromIp('192.168.1.' . mt_rand(1, 254));

        return $log;
    }

    private function getRandomEnvVersion(): EnvVersion
    {
        $versions = [
            EnvVersion::DEVELOP,
            EnvVersion::TRIAL,
            EnvVersion::RELEASE,
        ];

        return $versions[array_rand($versions)];
    }

    public function getDependencies(): array
    {
        return [
            ShareCodeFixtures::class,
        ];
    }
}
