<?php

namespace App\Tests\Character;

use App\Entity\CharacterData;
use App\Tools\Character\Factory\CharacterArrayFactory;
use App\Tools\Tests\AbstractKernelTest;

class CharacterCreationTest extends AbstractKernelTest
{
    protected function setUp(): void
    {
        $this->initialize();
    }

    public function getCharacterFromDB() : CharacterData
    {
        $repo = $this->entityManager->getRepository(CharacterData::class);

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

        foreach ($character->getCharacterStatValues() as $statValue) {
            $result = ($statValue->getValue() >= $statValue->getCharacterStat()->getLowestValue()
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

        foreach ($character->getCharacterClassLevels() as $classLevel) {
            $result = ($classLevel->getLevel() >= $classLevel->getCharacterClass()->getLowestLevel()
                && $classLevel->getLevel() <= $classLevel->getCharacterClass()->getHighestLevel());

            $this->assertTrue($result);
        }
    }

    public function testCharacterJSON()
    {
        $ruleSet = $this->ruleSetCreation(
            'App\Tools\Character\CyberpunkRed\CyberpunkCharacterArrayStrategy',
            'App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder'
        );
        $this->characterCreation($ruleSet);
        $character = $this->getCharacterFromDB();

        $characterJSON = CharacterArrayFactory::get($ruleSet);
        $json = $characterJSON->generateJSON($character);

        $this->assertIsArray($json);
        $this->assertEquals('1', $json['data']['ruleSet']->getId());
    }
}
