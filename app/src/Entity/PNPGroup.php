<?php

namespace App\Entity;

use App\Repository\PNPGroupRepository;
use App\Traits\JsonSerializer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PNPGroupRepository::class)]
class PNPGroup implements JsonSerializable
{
    use JsonSerializer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'pnpGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RuleSet $ruleSet = null;

    #[ORM\OneToMany(mappedBy: 'pnpGroup', targetEntity: CharacterData::class)]
    private Collection $characters;

    #[ORM\ManyToOne(inversedBy: 'gameMasterGroups')]
    private ?PNPUser $gameMaster = null;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRuleSet(): ?RuleSet
    {
        return $this->ruleSet;
    }

    public function setRuleSet(?RuleSet $ruleSet): static
    {
        $this->ruleSet = $ruleSet;

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
            $character->setPnpGroup($this);
        }

        return $this;
    }

    public function removeCharacter(CharacterData $character): static
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getPnpGroup() === $this) {
                $character->setPnpGroup(null);
            }
        }

        return $this;
    }

    public function getGameMaster(): ?PNPUser
    {
        return $this->gameMaster;
    }

    public function setGameMaster(?PNPUser $gameMaster): static
    {
        $this->gameMaster = $gameMaster;

        return $this;
    }
}
