<?php

namespace App\Entity;

use App\Repository\CharacterStatValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterStatValueRepository::class)]
class CharacterStatValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CharacterStat $characterStat = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\ManyToOne(inversedBy: 'characterStatValue')]
    private ?CharacterData $characterData = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacterStat(): ?CharacterStat
    {
        return $this->characterStat;
    }

    public function setCharacterStat(?CharacterStat $characterStat): static
    {
        $this->characterStat = $characterStat;

        return $this;
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
