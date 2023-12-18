<?php

namespace App\Entity;

use App\Repository\PNPUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PNPUserRepository::class)]
class PNPUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'gameMaster', targetEntity: PNPGroup::class)]
    private Collection $gameMasterGroups;

    public function __construct()
    {
        $this->gameMasterGroups = new ArrayCollection();
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
}
