<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character_data`')]
class CharacterData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'characterData', targetEntity: CharacterStatValue::class)]
    private Collection $characterStatValue;

    #[ORM\ManyToOne(inversedBy: 'characterData')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RuleSet $ruleSet = null;

    public function __construct()
    {
        $this->characterStatValue = new ArrayCollection();
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
     * @return Collection<int, CharacterStatValue>
     */
    public function getCharacterStatValue(): Collection
    {
        return $this->characterStatValue;
    }

    public function addCharacterStatValue(CharacterStatValue $characterStatValue): static
    {
        if (!$this->characterStatValue->contains($characterStatValue)) {
            $this->characterStatValue->add($characterStatValue);
            $characterStatValue->setCharacterData($this);
        }

        return $this;
    }

    public function removeCharacterStatValue(CharacterStatValue $characterStatValue): static
    {
        if ($this->characterStatValue->removeElement($characterStatValue)) {
            // set the owning side to null (unless already changed)
            if ($characterStatValue->getCharacterData() === $this) {
                $characterStatValue->setCharacterData(null);
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
