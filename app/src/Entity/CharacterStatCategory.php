<?php

namespace App\Entity;

use App\Repository\CharacterStatCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterStatCategoryRepository::class)]
class CharacterStatCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: CharacterStat::class, orphanRemoval: true)]
    private Collection $characterStats;

    #[ORM\ManyToOne(inversedBy: 'characterStatCategories')]
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
