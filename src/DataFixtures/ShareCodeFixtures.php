<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareCode;

#[When(env: 'test')]
#[When(env: 'dev')]
class ShareCodeFixtures extends Fixture implements FixtureGroupInterface
{
    public const CODE_REFERENCE_PREFIX = 'share_code_';
    public const CODE_COUNT = 15;

    public static function getGroups(): array
    {
        return ['wechat-mini-program-share'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::CODE_COUNT; ++$i) {
            $code = $this->createShareCode($i);
            $manager->persist($code);
            $this->addReference(self::CODE_REFERENCE_PREFIX . $i, $code);
        }

        $manager->flush();
    }

    private function createShareCode(int $index): ShareCode
    {
        $code = new ShareCode();

        $code->setLinkUrl('https://mp.weixin.qq.com/s/share_' . $index);
        $code->setImageUrl(null);
        $code->setEnvVersion($this->getRandomEnvVersion());
        $code->setValid(mt_rand(0, 10) > 1);
        $code->setSize(mt_rand(280, 1280));
        $code->setCreatedFromIp('192.168.1.' . mt_rand(1, 254));
        $code->setUpdatedFromIp('192.168.1.' . mt_rand(1, 254));
        $code->setCreateTime(new \DateTimeImmutable('-' . mt_rand(1, 30) . ' days'));
        $code->setUpdateTime(new \DateTimeImmutable('-' . mt_rand(1, 5) . ' days'));

        return $code;
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
}
