<?php

namespace App\Tests\Character;

use App\Entity\CharacterClass;
use App\Entity\CharacterClassLevel;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatCategory;
use App\Entity\CharacterStatValue;
use App\Entity\RuleSet;
use App\Tools\Tests\AbstractKernelTest;

class CharacterCreationTest extends AbstractKernelTest
{
    protected function setUp(): void
    {
        $this->Initialize();
    }

    private function characterCreation() : void
    {
        $ruleset = new RuleSet();
        $ruleset->setName("Cyberpunk Red");
        $ruleset->setVersion("1.0");

        $this->_entityManager->persist($ruleset);
        $this->_entityManager->flush();

        $character = new CharacterData();
        $character->setName("Darius");

        $ruleset->addCharacterData($character);

        $this->_entityManager->persist($character);
        $this->_entityManager->flush();

        $statCategory = new CharacterStatCategory();
        $statCategory->setName("Skills");

        $ruleset->addCharacterStatCategory($statCategory);

        $this->_entityManager->persist($statCategory);
        $this->_entityManager->flush();

        $stat1 = new CharacterStat();
        $stat1->setName("Stärke");
        $stat1->setMinValue(0);
        $stat1->setHighestValue(10);

        $stat2 = new CharacterStat();
        $stat2->setName("Geschick");
        $stat2->setMinValue(0);
        $stat2->setHighestValue(10);

        $stat3 = new CharacterStat();
        $stat3->setName("Kraft");
        $stat3->setMinValue(0);
        $stat3->setHighestValue(10);

        $statCategory->addCharacterStat($stat1);
        $statCategory->addCharacterStat($stat2);
        $statCategory->addCharacterStat($stat3);

        $this->_entityManager->persist($stat1);
        $this->_entityManager->persist($stat2);
        $this->_entityManager->persist($stat3);
        $this->_entityManager->flush();

        $statValue1 = new CharacterStatValue();
        $stat1->addCharacterStatValue($statValue1);
        $character->addCharacterStatValue($statValue1);
        $statValue1->setValue(-1);

        $statValue2 = new CharacterStatValue();
        $stat2->addCharacterStatValue($statValue2);
        $character->addCharacterStatValue($statValue2);
        $statValue2->setValue(11);

        $statValue3 = new CharacterStatValue();
        $stat2->addCharacterStatValue($statValue3);
        $character->addCharacterStatValue($statValue3);
        $statValue3->setValue(5);

        $this->_entityManager->persist($statValue1);
        $this->_entityManager->persist($statValue2);
        $this->_entityManager->persist($statValue3);
        $this->_entityManager->flush();

        $class = new CharacterClass();
        $class->setName("Solo");
        $class->setMinLevel(1);
        $class->setHighestLevel(20);
        $ruleset->addCharacterClass($class);

        $this->_entityManager->persist($class);
        $this->_entityManager->flush();

        $classLevel = new CharacterClassLevel();
        $class->addCharacterClassLevel($classLevel);
        $character->addCharacterClassLevel($classLevel);
        $classLevel->setLevel(0);

        $this->_entityManager->persist($classLevel);
        $this->_entityManager->flush();
    }

    public function getCharacterFromDB() : CharacterData
    {
        $repo = $this->_entityManager->getRepository(CharacterData::class);

        return $repo->findOneBy([
           "name" => "Darius"
        ]);
    }

    public function testCreation() : void
    {
        $this->characterCreation();
        $character = $this->getCharacterFromDB();

        $this->assertIsObject($character);
    }

    public function testCharacterRuleSet()
    {
        $this->characterCreation();
        $character = $this->getCharacterFromDB();

        $this->assertEquals("Cyberpunk Red", $character->getRuleSet()->getName());
    }

    public function testCharacterStats()
    {
        $this->characterCreation();
        $character = $this->getCharacterFromDB();

        foreach ($character->getCharacterStatValues() as $statValue)
        {
            $result = ($statValue->getValue() >= $statValue->getCharacterStat()->getMinValue()
                    && $statValue->getValue() <= $statValue->getCharacterStat()->getHighestValue());

            $this->assertTrue($result);
        }
    }

    public function testCharacterClass()
    {
        $this->characterCreation();
        $character = $this->getCharacterFromDB();

        $this->assertEquals("Solo", $character->getCharacterClassLevels()[0]->getCharacterClass()->getName());

        foreach ($character->getCharacterClassLevels() as $classLevel)
        {
            $result = ($classLevel->getLevel() >= $classLevel->getCharacterClass()->getMinLevel()
                && $classLevel->getLevel() <= $classLevel->getCharacterClass()->getHighestLevel());

            $this->assertTrue($result);
        }
    }
}