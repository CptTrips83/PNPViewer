<?php

namespace App\Tests\Character\CyberpunkRed;

use App\Entity\CharacterClass;
use App\Entity\CharacterClassLevel;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatCategory;
use App\Entity\CharacterStatValue;
use App\Entity\RuleSet;
use App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder;
use App\Tools\Character\Factory\CharacterBuilderFactory;
use App\Tools\Character\Factory\CharacterFactory;
use App\Tools\Tests\AbstractKernelTest;

class CharacterBuilderTest extends AbstractKernelTest
{
    protected function setUp(): void
    {
        $this->Initialize();
    }

    public function testCreateBuilderFromFactory() : void
    {
        $ruleSet = $this->ruleSetCreation(
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacter",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder"
        );

        $builder = CharacterBuilderFactory::get(
            $this->_entityManager,
            $ruleSet
        );

        $this->assertInstanceOf(CyberpunkCharacterBuilder::class, $builder);
    }

    public function testCreateCharacterFromFactory() : void
    {
        $this->ruleSetCreation(
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacter",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder"
        );

        $character = CharacterFactory::get();

        $this->assertInstanceOf(CharacterData::class, $character);
    }

    public function testBuildCharacter() : void
    {
        $ruleSet = $this->ruleSetCreation(
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacter",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder"
        );

        $builder = CharacterBuilderFactory::get(
            $this->_entityManager,
            $ruleSet
        );

        $characterClass = new CharacterClass();
        $characterClass->setRuleSet($ruleSet);
        $characterClass->setName("Solo");
        $characterClass->setMinLevel(1);
        $characterClass->setHighestLevel(1);

        $characterStatCategory = new CharacterStatCategory();
        $characterStatCategory->setName("Skills");
        $characterStatCategory->setStatsRequired(-1);
        $characterStatCategory->setRuleSet($ruleSet);

        $this->_entityManager->persist($characterStatCategory);
        $this->_entityManager->persist($characterClass);

        $characterStat = new CharacterStat();
        $characterStat->setName("Stärke");
        $characterStat->setMinValue(1);
        $characterStat->setHighestValue(1);
        $characterStatCategory->addCharacterStat($characterStat);

        $this->_entityManager->persist($characterStat);
        $this->_entityManager->flush();

        $character = $builder->setName("Darius")
            ->addClass($characterClass, 1)
            ->addStat($characterStat, 1)
            ->buildCharacter();

        $this->assertInstanceOf(CharacterData::class, $character);
        $this->assertEquals("Darius", $character->getName());
        $this->assertCount(1, $character->getCharacterClassLevels());
        $this->assertCount(1, $character->getCharacterStatValues());
    }
}