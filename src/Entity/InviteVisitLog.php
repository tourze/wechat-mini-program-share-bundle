<?php

namespace WechatMiniProgramShareBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use WechatMiniProgramBundle\Entity\LaunchOptionsAware;
use WechatMiniProgramShareBundle\Repository\InviteVisitLogRepository;

#[ORM\Entity(repositoryClass: InviteVisitLogRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_invite_visit_log', options: ['comment' => '邀请访问记录'])]
class InviteVisitLog implements \Stringable
{
    use LaunchOptionsAware;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '上下文'])]
    private ?array $context = [];

    #[IndexColumn]
    private ?string $shareOpenId = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?UserInterface $shareUser = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '分享时间'])]
    private ?\DateTimeImmutable $shareTime = null;

    #[IndexColumn]
    private ?string $visitOpenId = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '受邀时间'])]
    private ?\DateTimeImmutable $visitTime = null;

    #[ORM\Column(type: Types::STRING, length: 2000, options: ['comment' => '访问地址'])]
    private ?string $visitPath = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?UserInterface $visitUser = null;

    #[IndexColumn]
    private bool $newUser = false;

    #[ORM\Column(nullable: true, options: ['comment' => '是否已注册', 'default' => 0])]
    private ?bool $registered = null;

    #[CreateIpColumn]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    private ?string $updatedFromIp = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * @param array<string, mixed>|null $context
     */
    public function setContext(?array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getShareOpenId(): ?string
    {
        return $this->shareOpenId;
    }

    public function setShareOpenId(string $shareOpenId): self
    {
        $this->shareOpenId = $shareOpenId;

        return $this;
    }

    public function getShareTime(): ?\DateTimeImmutable
    {
        return $this->shareTime;
    }

    public function setShareTime(\DateTimeImmutable $shareTime): self
    {
        $this->shareTime = $shareTime;

        return $this;
    }

    public function getVisitOpenId(): ?string
    {
        return $this->visitOpenId;
    }

    public function setVisitOpenId(string $visitOpenId): self
    {
        $this->visitOpenId = $visitOpenId;

        return $this;
    }

    public function getVisitTime(): ?\DateTimeImmutable
    {
        return $this->visitTime;
    }

    public function setVisitTime(\DateTimeImmutable $visitTime): self
    {
        $this->visitTime = $visitTime;

        return $this;
    }

    public function getVisitPath(): ?string
    {
        return $this->visitPath;
    }

    public function setVisitPath(string $visitPath): self
    {
        $this->visitPath = $visitPath;

        return $this;
    }

    public function isNewUser(): bool
    {
        return $this->newUser;
    }

    public function setNewUser(bool $newUser): void
    {
        $this->newUser = $newUser;
    }

    public function getVisitUser(): ?UserInterface
    {
        return $this->visitUser;
    }

    public function setVisitUser(?UserInterface $visitUser): void
    {
        $this->visitUser = $visitUser;
    }

    public function getShareUser(): ?UserInterface
    {
        return $this->shareUser;
    }

    public function setShareUser(?UserInterface $shareUser): void
    {
        $this->shareUser = $shareUser;
    }

    public function isRegistered(): ?bool
    {
        return $this->registered;
    }

    public function setRegistered(?bool $registered): self
    {
        $this->registered = $registered;

        return $this;
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setCreatedFromIp(?string $createdFromIp): self
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    public function getUpdatedFromIp(): ?string
    {
        return $this->updatedFromIp;
    }

    public function setUpdatedFromIp(?string $updatedFromIp): self
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('InviteVisitLog[%s]', $this->id ?: 'new');
    }
}
