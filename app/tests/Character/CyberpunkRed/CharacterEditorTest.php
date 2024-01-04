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
use App\Tools\Character\CyberpunkRed\CyberpunkCharacterEditor;
use App\Tools\Character\Factory\CharacterBuilderFactory;
use App\Tools\Character\Factory\CharacterEditorFactory;
use App\Tools\Character\Factory\CharacterFactory;
use App\Tools\Tests\AbstractKernelTest;

class CharacterEditorTest extends AbstractKernelTest
{
    protected function setUp(): void
    {
        $this->Initialize();
    }

    public function testCreateEditorFromFactory() : void
    {
        $ruleSet = $this->ruleSetCreation(
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacter",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterBuilder",
            "App\Tools\Character\CyberpunkRed\CyberpunkCharacterEditor"
        );

        $builder = CharacterEditorFactory::get(
            $this->_entityManager,
            $ruleSet
        );

        $this->assertInstanceOf(CyberpunkCharacterEditor::class, $builder);
    }

    public function testEditCharacter() : void
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

        $editor = CharacterEditorFactory::get(
            $this->_entityManager,
            $ruleSet
        );

        $characterClass = new CharacterClass();
        $characterClass->setRuleSet($ruleSet);
        $characterClass->setName("Solo");
        $characterClass->setMinLevel(1);
        $characterClass->setHighestLevel(2);

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
        $characterStat1->setMinValue(1);
        $characterStat1->setHighestValue(5);
        $characterStatCategory->addCharacterStat($characterStat1);

        $characterStat2 = new CharacterStat();
        $characterStat2->setName("Int");
        $characterStat2->setMinValue(1);
        $characterStat2->setHighestValue(5);
        $characterStatCategory->addCharacterStat($characterStat2);

        $characterStat3 = new CharacterStat();
        $characterStat3->setName("Test");
        $characterStat3->setMinValue(1);
        $characterStat3->setHighestValue(5);
        $characterStatCategory2->addCharacterStat($characterStat3);

        $this->_entityManager->persist($characterStat1);
        $this->_entityManager->persist($characterStat2);
        $this->_entityManager->persist($characterStat3);
        $this->_entityManager->flush();

        $character = $builder->set("name", "Darius")
            ->addClass($characterClass, 1)
            ->addStat($characterStat1, 1)
            ->addStat($characterStat2, 1)
            ->addStat($characterStat3, 1)
            ->buildCharacter();

        $character = $editor->setCharacter($character)
            ->setClassLevel($characterClass, 2)
            ->setStatValue($characterStat1, 3)
            ->setStatValue($characterStat2, 4)
            ->setStatValue($characterStat3, 5)
            ->saveCharacter();

        $this->assertInstanceOf(CharacterData::class, $character);
        $this->assertEquals("Darius", $character->getName());
        $this->assertEquals(2, $character->getCharacterClassLevels()[0]->getLevel());
        $this->assertEquals(3, $character->getCharacterStatValues()[0]->getValue());
        $this->assertEquals(4, $character->getCharacterStatValues()[1]->getValue());
        $this->assertEquals(5, $character->getCharacterStatValues()[2]->getValue());

        $repo = $this->_entityManager->getRepository(CharacterData::class);

        $testCharacter = $repo->findOneBy(['name' => 'Darius']);

        $this->assertEquals(2, $testCharacter->getCharacterClassLevels()[0]->getLevel());
        $this->assertEquals(3, $testCharacter->getCharacterStatValues()[0]->getValue());
        $this->assertEquals(4, $testCharacter->getCharacterStatValues()[1]->getValue());
        $this->assertEquals(5, $testCharacter->getCharacterStatValues()[2]->getValue());
    }
}