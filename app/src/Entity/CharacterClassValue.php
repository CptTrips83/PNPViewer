<?php

namespace App\Entity;

use App\Repository\CharacterClassValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterClassValueRepository::class)]
class CharacterClassValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?int $value = null;

    #[ORM\ManyToOne(inversedBy: 'characterClassValues')]
    private ?RuleSetClass $ruleSetClass = null;

    #[ORM\ManyToOne(inversedBy: 'classValue')]
    private ?CharacterData $characterData = null;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getRuleSetClass(): ?RuleSetClass
    {
        return $this->ruleSetClass;
    }

    public function setRuleSetClass(?RuleSetClass $ruleSetClass): static
    {
        $this->ruleSetClass = $ruleSetClass;

        return $this;
    }

    public function getCharacterData(): ?CharacterData
    {
        return $this->characterData;
    }

    public function setCharacterData(?CharacterData $characterData): static
    {
        $this->characterData = $characterData;

        return $this;
    }
}
