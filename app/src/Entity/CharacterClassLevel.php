<?php

namespace App\Entity;

use App\Repository\CharacterClassLevelRepository;
use App\Traits\JsonSerializer;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: CharacterClassLevelRepository::class)]
class CharacterClassLevel implements JsonSerializable
{
    use JsonSerializer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'characterClassLevels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CharacterData $characterData = null;

    #[ORM\ManyToOne(inversedBy: 'characterClassLevels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CharacterClass $characterClass = null;

    #[ORM\Column]
    private ?int $level = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCharacterClass(): ?CharacterClass
    {
        return $this->characterClass;
    }

    public function setCharacterClass(?CharacterClass $characterClass): static
    {
        $this->characterClass = $characterClass;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $level = max($this->getCharacterClass()->getLowestLevel(), min($this->getCharacterClass()->getHighestLevel(), $level));

        $this->level = $level;

        return $this;
    }
}
