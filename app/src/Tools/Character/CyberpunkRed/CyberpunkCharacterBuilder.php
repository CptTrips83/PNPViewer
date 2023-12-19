<?php

namespace App\Tools\Character\CyberpunkRed;

use App\Entity\CharacterClass;
use App\Entity\CharacterClassLevel;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatValue;
use App\Entity\RuleSet;
use App\Tools\Character\Factory\CharacterFactory;
use App\Tools\Character\Interfaces\CharacterBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;

class CyberpunkCharacterBuilder implements CharacterBuilderInterface
{
    private CharacterData $_character;
    private EntityManagerInterface $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        RuleSet $ruleSet
    )
    {
        $this->_entityManager = $entityManager;
        $this->_character = CharacterFactory::get();
        $this->_character->setRuleSet($ruleSet);
        $this->_character->setName("");
        $this->_entityManager->persist($this->_character);
    }

    /*
     * @inheritdoc
     */
    public function setCharacter(CharacterData $character): CharacterBuilderInterface
    {
        $this->_character = $character;

        return $this;
    }

    public function set(string $property, mixed $value) : CharacterBuilderInterface
    {
        $setter = "set".ucfirst($property);

        if(method_exists($this->_character, $setter))
        {
            $this->_character->$setter($value);
        }

        return $this;
    }

    /*
     * @inheritdoc
     */
    public function setName(string $name): CharacterBuilderInterface
    {
        $this->_character->setName($name);
        return $this;
    }

    /*
     * @inheritdoc
     */
    public function addClass(CharacterClass $class, int $level): CharacterBuilderInterface
    {
        $classLevel = new CharacterClassLevel();
        $class->addCharacterClassLevel($classLevel);
        $classLevel->setLevel($level);
        $this->_character->addCharacterClassLevel($classLevel);

        $this->_entityManager->persist($classLevel);
        $this->_entityManager->flush();
        return $this;
    }

    /*
     * @inheritdoc
     */
    public function addStat(CharacterStat $stat, int $value): CharacterBuilderInterface
    {
        $statValue = new CharacterStatValue();

        if($stat->getCategory()->getStatsRequired() == 1) {
            $repo = $this->_entityManager->getRepository(CharacterStatValue::class);

            $statValueTemp = $repo->findOneBy([
                'characterData' => $this->_character,
                'characterStat' => $stat
            ]);

            if($statValueTemp != null) $statValue = $statValueTemp;
        }
        if($statValue->getValue() == null) {
            $stat->addCharacterStatValue($statValue);
        }

        $statValue->setValue($value);
        $this->_character->addCharacterStatValue($statValue);

        $this->_entityManager->persist($this->_character);
        $this->_entityManager->persist($statValue);
        $this->_entityManager->flush();
        return $this;
    }

    /*
     * @inheritdoc
     */
    public function buildCharacter(): CharacterData
    {
        $this->_entityManager->persist($this->_character);
        $this->_entityManager->flush();

        return $this->_character;
    }
}