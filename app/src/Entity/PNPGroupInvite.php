<?php

namespace App\Entity;

use App\Repository\PNPGroupInviteRepository;
use DateInterval;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PNPGroupInviteRepository::class)]
class PNPGroupInvite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $inviteCode = null;

    #[ORM\ManyToOne(inversedBy: 'groupInvites')]
    private ?PNPUser $invitedUser = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $inviteDate = null;

    #[ORM\Column]
    private ?DateInterval $inviteLifeTime = null;

    #[ORM\ManyToOne(inversedBy: 'invites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PNPGroup $inviteGroup = null;

    #[ORM\Column]
    private ?int $inviteCodeShort = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInviteCode(): ?string
    {
        return $this->inviteCode;
    }

    public function setInviteCode(string $inviteCode): static
    {
        $this->inviteCode = $inviteCode;

        return $this;
    }

    public function getInvitedUser(): ?PNPUser
    {
        return $this->invitedUser;
    }

    public function setInvitedUser(?PNPUser $invitedUser): static
    {
        $this->invitedUser = $invitedUser;

        return $this;
    }

    public function getInviteDate(): ?DateTimeInterface
    {
        return $this->inviteDate;
    }

    public function setInviteDate(DateTimeInterface $inviteDate): static
    {
        $this->inviteDate = $inviteDate;

        return $this;
    }

    public function getInviteLifeTime(): ?DateInterval
    {
        return $this->inviteLifeTime;
    }

    public function setInviteLifeTime(DateInterval $inviteLifeTime): static
    {
        $this->inviteLifeTime = $inviteLifeTime;

        return $this;
    }

    public function getInviteGroup(): ?PNPGroup
    {
        return $this->inviteGroup;
    }

    public function setInviteGroup(?PNPGroup $inviteGroup): static
    {
        $this->inviteGroup = $inviteGroup;

        return $this;
    }

    public function getInviteCodeShort(): ?int
    {
        return $this->inviteCodeShort;
    }

    public function setInviteCodeShort(int $inviteCodeShort): static
    {
        $this->inviteCodeShort = $inviteCodeShort;

        return $this;
    }
}
