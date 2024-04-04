<?php

namespace App\Tools\Tests;

use App\Entity\CharacterClass;
use App\Entity\CharacterClassLevel;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatCategory;
use App\Entity\CharacterStatValue;
use App\Entity\RuleSet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractKernelTest extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    protected function initialize() : void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function ruleSetCreation(
        string $characterJSONName = "",
        string $characterBuilderName = "",
        string $characterEditorName = ""
    ) : RuleSet {
        $ruleset = new RuleSet();
        $ruleset->setName("Cyberpunk Red");
        $ruleset->setVersion("1.0");
        $ruleset->setCharacterArrayName($characterJSONName);
        $ruleset->setCharacterBuilderName($characterBuilderName);
        $ruleset->setCharacterEditorName($characterEditorName);

        $this->entityManager->persist($ruleset);
        $this->entityManager->flush();

        return $ruleset;
    }

    protected function characterCreation(RuleSet $ruleSet) : void
    {
        $character = new CharacterData();
        $character->setName("Darius");

        $ruleSet->addCharacterData($character);

        $this->entityManager->persist($character);
        $this->entityManager->flush();

        $statCategory = new CharacterStatCategory();
        $statCategory->setName("Skills");
        $statCategory->setStatsRequired(-1);

        $ruleSet->addCharacterStatCategory($statCategory);

        $this->entityManager->persist($statCategory);
        $this->entityManager->flush();

        $stat1 = new CharacterStat();
        $stat1->setName("Stärke");
        $stat1->setLowestValue(0);
        $stat1->setHighestValue(10);

        $stat2 = new CharacterStat();
        $stat2->setName("Geschick");
        $stat2->setLowestValue(0);
        $stat2->setHighestValue(10);

        $stat3 = new CharacterStat();
        $stat3->setName("Kraft");
        $stat3->setLowestValue(0);
        $stat3->setHighestValue(10);

        $statCategory->addCharacterStat($stat1);
        $statCategory->addCharacterStat($stat2);
        $statCategory->addCharacterStat($stat3);

        $this->entityManager->persist($stat1);
        $this->entityManager->persist($stat2);
        $this->entityManager->persist($stat3);
        $this->entityManager->flush();

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

        $this->entityManager->persist($statValue1);
        $this->entityManager->persist($statValue2);
        $this->entityManager->persist($statValue3);
        $this->entityManager->flush();

        $class = new CharacterClass();
        $class->setName("Solo");
        $class->setLowestLevel(1);
        $class->setHighestLevel(20);
        $ruleSet->addCharacterClass($class);

        $this->entityManager->persist($class);
        $this->entityManager->flush();

        $classLevel = new CharacterClassLevel();
        $class->addCharacterClassLevel($classLevel);
        $character->addCharacterClassLevel($classLevel);
        $classLevel->setLevel(0);

        $this->entityManager->persist($classLevel);
        $this->entityManager->flush();
    }
}
