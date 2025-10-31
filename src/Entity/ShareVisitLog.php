<?php

namespace WechatMiniProgramShareBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Traits\CreatedFromIpAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use WechatMiniProgramBundle\Entity\LaunchOptionsAware;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Repository\ShareVisitLogRepository;

#[ORM\Entity(repositoryClass: ShareVisitLogRepository::class, readOnly: true)]
#[ORM\Table(name: 'wechat_mini_program_share_visit_log', options: ['comment' => '分享码访问记录'])]
class ShareVisitLog implements \Stringable
{
    use LaunchOptionsAware;
    use SnowflakeKeyAware;
    use CreatedFromIpAware;

    #[ORM\ManyToOne(targetEntity: ShareCode::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShareCode $code = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true, enumType: EnvVersion::class, options: ['comment' => '环境版本'])]
    #[Assert\Type(type: EnvVersion::class)]
    #[Assert\Choice(callback: [EnvVersion::class, 'cases'])]
    private ?EnvVersion $envVersion = null;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: Types::JSON, options: ['comment' => '字段说明'])]
    #[Assert\Type(type: 'array')]
    private array $response = [];

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?UserInterface $user = null;

    #[IndexColumn]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeImmutable $createTime = null;

    public function getEnvVersion(): ?EnvVersion
    {
        return $this->envVersion;
    }

    public function setEnvVersion(?EnvVersion $envVersion): void
    {
        $this->envVersion = $envVersion;
    }

    public function getCode(): ?ShareCode
    {
        return $this->code;
    }

    public function setCode(?ShareCode $code): void
    {
        $this->code = $code;
    }

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param array<string, mixed> $response
     */
    public function setResponse(array $response): void
    {
        $this->response = $response;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function setCreateTime(?\DateTimeImmutable $createdAt): void
    {
        $this->createTime = $createdAt;
    }

    public function getCreateTime(): ?\DateTimeImmutable
    {
        return $this->createTime;
    }

    public function __toString(): string
    {
        return sprintf('ShareVisitLog[%s]', $this->id ?? 'new');
    }
}
