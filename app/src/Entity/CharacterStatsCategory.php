<?php

namespace App\Entity;

use App\Repository\CharacterStatsCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterStatsCategoryRepository::class)]
class CharacterStatsCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'Category', targetEntity: CharacterStat::class, orphanRemoval: true)]
    private Collection $characterStats;

    #[ORM\ManyToOne(inversedBy: 'characterStatsCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RuleSet $ruleSet = null;

    public function __construct()
    {
        $this->characterStats = new ArrayCollection();
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
     * @return Collection<int, CharacterStat>
     */
    public function getCharacterStats(): Collection
    {
        return $this->characterStats;
    }

    public function addCharacterStat(CharacterStat $characterStat): static
    {
        if (!$this->characterStats->contains($characterStat)) {
            $this->characterStats->add($characterStat);
            $characterStat->setCategory($this);
        }

        return $this;
    }

    public function removeCharacterStat(CharacterStat $characterStat): static
    {
        if ($this->characterStats->removeElement($characterStat)) {
            // set the owning side to null (unless already changed)
            if ($characterStat->getCategory() === $this) {
                $characterStat->setCategory(null);
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
}
