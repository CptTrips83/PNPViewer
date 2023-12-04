<?php

namespace App\Entity;

use App\Repository\RuleSetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'ruleSet', targetEntity: CharacterData::class, orphanRemoval: true)]
    private Collection $characterData;

    #[ORM\OneToMany(mappedBy: 'ruleSet', targetEntity: CharacterStatsCategory::class, orphanRemoval: true)]
    private Collection $characterStatsCategories;

    public function __construct()
    {
        $this->characterData = new ArrayCollection();
        $this->characterStatsCategories = new ArrayCollection();
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
     * @return Collection<int, CharacterStatsCategory>
     */
    public function getCharacterStatsCategories(): Collection
    {
        return $this->characterStatsCategories;
    }

    public function addCharacterStatsCategory(CharacterStatsCategory $characterStatsCategory): static
    {
        if (!$this->characterStatsCategories->contains($characterStatsCategory)) {
            $this->characterStatsCategories->add($characterStatsCategory);
            $characterStatsCategory->setRuleSet($this);
        }

        return $this;
    }

    public function removeCharacterStatsCategory(CharacterStatsCategory $characterStatsCategory): static
    {
        if ($this->characterStatsCategories->removeElement($characterStatsCategory)) {
            // set the owning side to null (unless already changed)
            if ($characterStatsCategory->getRuleSet() === $this) {
                $characterStatsCategory->setRuleSet(null);
            }
        }

        return $this;
    }
}
