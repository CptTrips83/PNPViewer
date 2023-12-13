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
    private Collection $statValue;

    #[ORM\ManyToOne(inversedBy: 'characterData')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RuleSet $ruleSet = null;

    #[ORM\OneToMany(mappedBy: 'characterData', targetEntity: CharacterClassValue::class)]
    private Collection $classValue;

    public function __construct()
    {
        $this->statValue = new ArrayCollection();
        $this->classValue = new ArrayCollection();
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
    public function getStatValue(): Collection
    {
        return $this->statValue;
    }

    public function addStatValue(CharacterStatValue $characterStatValue): static
    {
        if (!$this->statValue->contains($characterStatValue)) {
            $this->statValue->add($characterStatValue);
            $characterStatValue->setCharacterData($this);
        }

        return $this;
    }

    public function removeStatValue(CharacterStatValue $characterStatValue): static
    {
        if ($this->statValue->removeElement($characterStatValue)) {
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

    /**
     * @return Collection<int, CharacterClassValue>
     */
    public function getClassValue(): Collection
    {
        return $this->classValue;
    }

    public function addClassValue(CharacterClassValue $classValue): static
    {
        if (!$this->classValue->contains($classValue)) {
            $this->classValue->add($classValue);
            $classValue->setCharacterData($this);
        }

        return $this;
    }

    public function removeClassValue(CharacterClassValue $classValue): static
    {
        if ($this->classValue->removeElement($classValue)) {
            // set the owning side to null (unless already changed)
            if ($classValue->getCharacterData() === $this) {
                $classValue->setCharacterData(null);
            }
        }

        return $this;
    }
}
