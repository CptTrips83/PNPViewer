<?php

namespace App\Entity;

use App\Repository\RuleSetClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RuleSetClassRepository::class)]
class RuleSetClass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'class')]
    private ?RuleSet $ruleSet = null;

    #[ORM\OneToMany(mappedBy: 'ruleSetClass', targetEntity: CharacterClassValue::class)]
    private Collection $characterClassValues;

    public function __construct()
    {
        $this->characterClassValues = new ArrayCollection();
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
     * @return Collection<int, CharacterClassValue>
     */
    public function getCharacterClassValues(): Collection
    {
        return $this->characterClassValues;
    }

    public function addCharacterClassValue(CharacterClassValue $characterClassValue): static
    {
        if (!$this->characterClassValues->contains($characterClassValue)) {
            $this->characterClassValues->add($characterClassValue);
            $characterClassValue->setRuleSetClass($this);
        }

        return $this;
    }

    public function removeCharacterClassValue(CharacterClassValue $characterClassValue): static
    {
        if ($this->characterClassValues->removeElement($characterClassValue)) {
            // set the owning side to null (unless already changed)
            if ($characterClassValue->getRuleSetClass() === $this) {
                $characterClassValue->setRuleSetClass(null);
            }
        }

        return $this;
    }

}
