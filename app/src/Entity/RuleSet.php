<?php

namespace App\Entity;

use App\Repository\RuleSetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RuleSetRepository::class)]
class RuleSet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $version = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'ruleSet', targetEntity: CharacterStatCategory::class, orphanRemoval: true)]
    private Collection $characterStatCategories;

    #[ORM\OneToMany(mappedBy: 'ruleSet', targetEntity: CharacterData::class, orphanRemoval: true)]
    private Collection $characterData;

    #[ORM\OneToMany(mappedBy: 'ruleSet', targetEntity: CharacterClass::class, orphanRemoval: true)]
    private Collection $characterClasses;

    #[ORM\OneToMany(mappedBy: 'ruleSet', targetEntity: PNPGroup::class, orphanRemoval: true)]
    private Collection $pnpGroups;

    public function __construct()
    {
        $this->characterStatCategories = new ArrayCollection();
        $this->characterData = new ArrayCollection();
        $this->characterClasses = new ArrayCollection();
        $this->pnpGroups = new ArrayCollection();
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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): static
    {
        $this->version = $version;

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

    /**
     * @return Collection<int, CharacterStatCategory>
     */
    public function getCharacterStatCategories(): Collection
    {
        return $this->characterStatCategories;
    }

    public function addCharacterStatCategory(CharacterStatCategory $characterStatCategory): static
    {
        if (!$this->characterStatCategories->contains($characterStatCategory)) {
            $this->characterStatCategories->add($characterStatCategory);
            $characterStatCategory->setRuleSet($this);
        }

        return $this;
    }

    public function removeCharacterStatCategory(CharacterStatCategory $characterStatCategory): static
    {
        if ($this->characterStatCategories->removeElement($characterStatCategory)) {
            // set the owning side to null (unless already changed)
            if ($characterStatCategory->getRuleSet() === $this) {
                $characterStatCategory->setRuleSet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CharacterData>
     */
    public function getCharacterData(): Collection
    {
        return $this->characterData;
    }

    public function addCharacterData(CharacterData $characterData): static
    {
        if (!$this->characterData->contains($characterData)) {
            $this->characterData->add($characterData);
            $characterData->setRuleSet($this);
        }

        return $this;
    }

    public function removeCharacterData(CharacterData $characterData): static
    {
        if ($this->characterData->removeElement($characterData)) {
            // set the owning side to null (unless already changed)
            if ($characterData->getRuleSet() === $this) {
                $characterData->setRuleSet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CharacterClass>
     */
    public function getCharacterClasses(): Collection
    {
        return $this->characterClasses;
    }

    public function addCharacterClass(CharacterClass $characterClass): static
    {
        if (!$this->characterClasses->contains($characterClass)) {
            $this->characterClasses->add($characterClass);
            $characterClass->setRuleSet($this);
        }

        return $this;
    }

    public function removeCharacterClass(CharacterClass $characterClass): static
    {
        if ($this->characterClasses->removeElement($characterClass)) {
            // set the owning side to null (unless already changed)
            if ($characterClass->getRuleSet() === $this) {
                $characterClass->setRuleSet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PNPGroup>
     */
    public function getPnpGroups(): Collection
    {
        return $this->pnpGroups;
    }

    public function addPnpGroup(PNPGroup $pnpGroup): static
    {
        if (!$this->pnpGroups->contains($pnpGroup)) {
            $this->pnpGroups->add($pnpGroup);
            $pnpGroup->setRuleSet($this);
        }

        return $this;
    }

    public function removePnpGroup(PNPGroup $pnpGroup): static
    {
        if ($this->pnpGroups->removeElement($pnpGroup)) {
            // set the owning side to null (unless already changed)
            if ($pnpGroup->getRuleSet() === $this) {
                $pnpGroup->setRuleSet(null);
            }
        }

        return $this;
    }
}
