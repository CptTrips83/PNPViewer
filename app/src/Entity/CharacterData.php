<?php

namespace App\Entity;

use App\Repository\CharacterDataRepository;
use App\Traits\JsonSerializer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterDataRepository::class)]
class CharacterData implements \JsonSerializable
{
    use JsonSerializer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'characterData', targetEntity: CharacterStatValue::class, orphanRemoval: true)]
    private Collection $characterStatValues;

    #[ORM\OneToMany(mappedBy: 'characterData', targetEntity: CharacterClassLevel::class, orphanRemoval: true)]
    private Collection $characterClassLevels;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'characterData')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RuleSet $ruleSet = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    private ?PNPGroup $pnpGroup = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    private ?PNPUser $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creationStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creationEnd = null;

    public function __construct()
    {
        $this->characterStatValues = new ArrayCollection();
        $this->characterClassLevels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $characterStatValue->setCharacterData($this);
        }

        return $this;
    }

    public function removeCharacterStatValue(CharacterStatValue $characterStatValue): static
    {
        if ($this->characterStatValues->removeElement($characterStatValue)) {
            // set the owning side to null (unless already changed)
            if ($characterStatValue->getCharacterData() === $this) {
                $characterStatValue->setCharacterData(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CharacterClassLevel>
     */
    public function getCharacterClassLevels(): Collection
    {
        return $this->characterClassLevels;
    }

    public function addCharacterClassLevel(CharacterClassLevel $characterClassLevel): static
    {
        if (!$this->characterClassLevels->contains($characterClassLevel)) {
            $this->characterClassLevels->add($characterClassLevel);
            $characterClassLevel->setCharacterData($this);
        }

        return $this;
    }

    public function removeCharacterClassLevel(CharacterClassLevel $characterClassLevel): static
    {
        if ($this->characterClassLevels->removeElement($characterClassLevel)) {
            // set the owning side to null (unless already changed)
            if ($characterClassLevel->getCharacterData() === $this) {
                $characterClassLevel->setCharacterData(null);
            }
        }

        return $this;
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

    public function getRuleSet(): ?RuleSet
    {
        return $this->ruleSet;
    }

    public function setRuleSet(?RuleSet $ruleSet): static
    {
        $this->ruleSet = $ruleSet;

        return $this;
    }

    public function getPnpGroup(): ?PNPGroup
    {
        return $this->pnpGroup;
    }

    public function setPnpGroup(?PNPGroup $pnpGroup): static
    {
        $this->pnpGroup = $pnpGroup;

        return $this;
    }

    public function getUser(): ?PNPUser
    {
        return $this->user;
    }

    public function setUser(?PNPUser $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getCreationStart(): ?\DateTimeInterface
    {
        return $this->creationStart;
    }

    public function setCreationStart(?\DateTimeInterface $creationStart): static
    {
        $this->creationStart = $creationStart;

        return $this;
    }

    public function getCreationEnd(): ?\DateTimeInterface
    {
        return $this->creationEnd;
    }

    public function setCreationEnd(?\DateTimeInterface $creationEnd): static
    {
        $this->creationEnd = $creationEnd;

        return $this;
    }
}
