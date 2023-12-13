<?php

namespace App\Entity;

use App\Repository\CharacterStatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterStatRepository::class)]
class CharacterStat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $minValue = null;

    #[ORM\Column]
    private ?int $highestValue = null;

    #[ORM\ManyToOne(inversedBy: 'characterStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CharacterStatCategory $category = null;

    #[ORM\OneToMany(mappedBy: 'characterStat', targetEntity: CharacterStatValue::class, orphanRemoval: true)]
    private Collection $characterStatValues;

    public function __construct()
    {
        $this->characterStatValues = new ArrayCollection();
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

    public function getMinValue(): ?int
    {
        return $this->minValue;
    }

    public function setMinValue(int $minValue): static
    {
        $this->minValue = $minValue;

        return $this;
    }

    public function getHighestValue(): ?int
    {
        return $this->highestValue;
    }

    public function setHighestValue(int $highestValue): static
    {
        $this->highestValue = $highestValue;

        return $this;
    }

    public function getCategory(): ?CharacterStatCategory
    {
        return $this->category;
    }

    public function setCategory(?CharacterStatCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, CharacterStatValue>
     */
    public function getCharacterStatValues(): Collection
    {
        return $this->characterStatValues;
    }

    public function addCharacterStatValue(CharacterStatValue $characterStatValue): static
    {
        if (!$this->characterStatValues->contains($characterStatValue)) {
            $this->characterStatValues->add($characterStatValue);
            $characterStatValue->setCharacterStat($this);
        }

        return $this;
    }

    public function removeCharacterStatValue(CharacterStatValue $characterStatValue): static
    {
        if ($this->characterStatValues->removeElement($characterStatValue)) {
            // set the owning side to null (unless already changed)
            if ($characterStatValue->getCharacterStat() === $this) {
                $characterStatValue->setCharacterStat(null);
            }
        }

        return $this;
    }
}
