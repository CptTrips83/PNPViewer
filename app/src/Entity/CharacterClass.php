<?php

namespace App\Entity;

use App\Repository\CharacterClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterClassRepository::class)]
class CharacterClass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $minLevel = null;

    #[ORM\Column]
    private ?int $highestLevel = null;

    #[ORM\OneToMany(mappedBy: 'characterClass', targetEntity: CharacterClassLevel::class, orphanRemoval: true)]
    private Collection $characterClassLevels;

    #[ORM\ManyToOne(inversedBy: 'characterClasses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RuleSet $ruleSet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $longDescription = null;

    public function __construct()
    {
        $this->characterClassLevels = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getDescription();
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

    public function getMinLevel(): ?int
    {
        return $this->minLevel;
    }

    public function setMinLevel(int $minLevel): static
    {
        $this->minLevel = $minLevel;

        return $this;
    }

    public function getHighestLevel(): ?int
    {
        return $this->highestLevel;
    }

    public function setHighestLevel(int $highestLevel): static
    {
        $this->highestLevel = $highestLevel;

        return $this;
    }

    /**
     * @return Collection<int, CharacterClassLevel>
     */
    public function getCharacterClassLevels(): Collection
    {
        return $this->characterClassLevels;
    }

    public function addCharacterClassLevel(CharacterClassLevel $characterClassLevel): static
    {
        if (!$this->characterClassLevels->contains($characterClassLevel)) {
            $this->characterClassLevels->add($characterClassLevel);
            $characterClassLevel->setCharacterClass($this);
        }

        return $this;
    }

    public function removeCharacterClassLevel(CharacterClassLevel $characterClassLevel): static
    {
        if ($this->characterClassLevels->removeElement($characterClassLevel)) {
            // set the owning side to null (unless already changed)
            if ($characterClassLevel->getCharacterClass() === $this) {
                $characterClassLevel->setCharacterClass(null);
            }
        }

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

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(?string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
    }
}
