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



    public function getCharacterFromDB() : CharacterData
    {
        $repo = $this->_entityManager->getRepository(CharacterData::class);

        return $repo->findOneBy([
           "name" => "Darius"
        ]);
    }

    public function testCreation() : void
    {
        $ruleSet = $this->ruleSetCreation();
        $this->characterCreation($ruleSet);
        $character = $this->getCharacterFromDB();

        $this->assertIsObject($character);
    }

    public function testCharacterRuleSet()
    {
        $ruleSet = $this->ruleSetCreation();
        $this->characterCreation($ruleSet);
        $character = $this->getCharacterFromDB();

        $this->assertEquals("Cyberpunk Red", $character->getRuleSet()->getName());
    }

    public function testCharacterStats()
    {
        $ruleSet = $this->ruleSetCreation();
        $this->characterCreation($ruleSet);
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
        $ruleSet = $this->ruleSetCreation();
        $this->characterCreation($ruleSet);
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