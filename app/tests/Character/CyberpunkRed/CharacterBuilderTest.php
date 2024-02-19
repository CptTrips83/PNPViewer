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
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterEditor"
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
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterEditor"
        );

        $character = CharacterFactory::get();

        $this->assertInstanceOf(CharacterData::class, $character);
    }

    public function testBuildCharacter() : void
    {
        $ruleSet = $this->ruleSetCreation(
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacter",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterEditor"
        );

        $builder1 = CharacterBuilderFactory::get(
            $this->_entityManager,
            $ruleSet
        );

        $builder2 = CharacterBuilderFactory::get(
            $this->_entityManager,
            $ruleSet
        );

        $characterClass = new CharacterClass();
        $characterClass->setRuleSet($ruleSet);
        $characterClass->setName("Solo");
        $characterClass->setLowestLevel(1);
        $characterClass->setHighestLevel(1);

        $characterStatCategory = new CharacterStatCategory();
        $characterStatCategory->setName("Skills");
        $characterStatCategory->setStatsRequired(-1);
        $characterStatCategory->setRuleSet($ruleSet);

        $characterStatCategory2 = new CharacterStatCategory();
        $characterStatCategory2->setName("Test");
        $characterStatCategory2->setStatsRequired(1);
        $characterStatCategory2->setRuleSet($ruleSet);

        $this->_entityManager->persist($characterStatCategory);
        $this->_entityManager->persist($characterStatCategory2);
        $this->_entityManager->persist($characterClass);

        $characterStat1 = new CharacterStat();
        $characterStat1->setName("Stärke");
        $characterStat1->setLowestValue(1);
        $characterStat1->setHighestValue(1);
        $characterStatCategory->addCharacterStat($characterStat1);

        $characterStat2 = new CharacterStat();
        $characterStat2->setName("Int");
        $characterStat2->setLowestValue(1);
        $characterStat2->setHighestValue(1);
        $characterStatCategory->addCharacterStat($characterStat2);

        $characterStat3 = new CharacterStat();
        $characterStat3->setName("Test");
        $characterStat3->setLowestValue(1);
        $characterStat3->setHighestValue(1);
        $characterStatCategory2->addCharacterStat($characterStat3);

        $this->_entityManager->persist($characterStat1);
        $this->_entityManager->persist($characterStat2);
        $this->_entityManager->persist($characterStat3);
        $this->_entityManager->flush();

        $character = $builder1->set("name", "Darius")
            ->addClass($characterClass, 1)
            ->addStat($characterStat1, 1)
            ->addStat($characterStat3, 1)
            ->buildCharacter();

        $character = $builder2->setCharacter($character)
            ->addStat($characterStat2, 1)
            ->addStat($characterStat3, 1)
            ->buildCharacter();

        $this->assertInstanceOf(CharacterData::class, $character);
        $this->assertEquals("Darius", $character->getName());
        $this->assertCount(1, $character->getCharacterClassLevels());
        $this->assertCount(3, $character->getCharacterStatValues());
    }
}