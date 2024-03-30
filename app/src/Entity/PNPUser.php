<?php

namespace App\Entity;

use App\Repository\PNPUserRepository;
use App\Traits\JsonSerializer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PNPUserRepository::class)]
class PNPUser implements UserInterface, PasswordAuthenticatedUserInterface, JsonSerializable
{
    use JsonSerializer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'gameMaster', targetEntity: PNPGroup::class)]
    private Collection $gameMasterGroups;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CharacterData::class)]
    private Collection $characters;

    #[ORM\OneToMany(mappedBy: 'invitedUser', targetEntity: PNPGroupInvite::class)]
    private Collection $groupInvites;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    public function __construct()
    {
        $this->gameMasterGroups = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->groupInvites = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, PNPGroup>
     */
    public function getGameMasterGroups(): Collection
    {
        return $this->gameMasterGroups;
    }

    public function addGameMasterGroup(PNPGroup $gameMasterGroup): static
    {
        if (!$this->gameMasterGroups->contains($gameMasterGroup)) {
            $this->gameMasterGroups->add($gameMasterGroup);
            $gameMasterGroup->setGameMaster($this);
        }

        return $this;
    }

    public function removeGameMasterGroup(PNPGroup $gameMasterGroup): static
    {
        if ($this->gameMasterGroups->removeElement($gameMasterGroup)) {
            // set the owning side to null (unless already changed)
            if ($gameMasterGroup->getGameMaster() === $this) {
                $gameMasterGroup->setGameMaster(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CharacterData>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(CharacterData $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->setUser($this);
        }

        return $this;
    }

    public function removeCharacter(CharacterData $character): static
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getUser() === $this) {
                $character->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PNPGroupInvite>
     */
    public function getGroupInvites(): Collection
    {
        return $this->groupInvites;
    }

    public function addGroupInvite(PNPGroupInvite $groupInvite): static
    {
        if (!$this->groupInvites->contains($groupInvite)) {
            $this->groupInvites->add($groupInvite);
            $groupInvite->setInvitedUser($this);
        }

        return $this;
    }

    public function removeGroupInvite(PNPGroupInvite $groupInvite): static
    {
        if ($this->groupInvites->removeElement($groupInvite)) {
            // set the owning side to null (unless already changed)
            if ($groupInvite->getInvitedUser() === $this) {
                $groupInvite->setInvitedUser(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
