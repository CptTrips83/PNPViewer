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

    /**
     * Speichert den Namen des Characters
     * @param string $name Name des Characters
     * @return CharacterBuilderInterface
     */
    public function setName(string $name): CharacterBuilderInterface
    {
        $this->_character->setName($name);
        return $this;
    }

    /**
     * Fügt eine Klasse mit dem angegebenen Level ein
     * @param CharacterClass $class Die Klasse die eingefügt werden soll
     * @param int $level Der Level den die Klasse hat
     * @return CharacterBuilderInterface
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

    /**
     * Fügt einen Stat mit dem angegebenen Value ein
     * @param CharacterStat $stat Der Stat der eingefügt werden soll
     * @param int $value Der Value den der Stat haben soll
     * @return CharacterBuilderInterface
     */
    public function addStat(CharacterStat $stat, int $value): CharacterBuilderInterface
    {
        $statValue = new CharacterStatValue();
        $stat->addCharacterStatValue($statValue);
        $statValue->setValue($value);
        $this->_character->addCharacterStatValue($statValue);

        $this->_entityManager->persist($this->_character);
        $this->_entityManager->persist($statValue);
        $this->_entityManager->flush();
        return $this;
    }

    /**
     * Generiert den Character
     * @return CharacterData Der fertige Character
     */
    public function buildCharacter(): CharacterData
    {
        $this->_entityManager->persist($this->_character);
        $this->_entityManager->flush();

        return $this->_character;
    }
}