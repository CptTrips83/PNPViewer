<?php

namespace App\Tools\Tests;

use App\Entity\CharacterClass;
use App\Entity\CharacterClassLevel;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatCategory;
use App\Entity\CharacterStatValue;
use App\Entity\PNPGroup;
use App\Entity\PNPUser;
use App\Entity\RuleSet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractWebTest extends WebTestCase
{
    protected EntityManagerInterface $_entityManager;
    protected KernelBrowser $_client;

    protected function Initialize() : void
    {
        $this->_client = self::createClient();
        $kernel = $this->_client->getKernel();
        DatabasePrimer::prime($kernel);

        $this->_entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $ruleSet = $this->ruleSetCreation();
        $this->groupCreation($ruleSet);
        $this->userCreation();
    }

    protected function ruleSetCreation(
        string $characterJSONName = "",
        string $characterBuilderName = "",
        string $characterEditorName = ""
    ) : RuleSet
    {
        $ruleset = new RuleSet();
        $ruleset->setName("Cyberpunk Red");
        $ruleset->setVersion("1.0");
        $ruleset->setCharacterArrayName($characterJSONName);
        $ruleset->setCharacterBuilderName($characterBuilderName);
        $ruleset->setCharacterEditorName($characterEditorName);

        $this->_entityManager->persist($ruleset);
        $this->_entityManager->flush();

        return $ruleset;
    }

    protected function userCreation() : void
    {
        $user = new PNPUser();
        $user->setUsername("test");
        $user->setPassword("password123");
        $user->setEmail("jan-peter.wittig@live.com");

        $this->_entityManager->persist($user);
        $this->_entityManager->flush();
    }

    protected function groupCreation(RuleSet $ruleSet) : void
    {
        $group = new PNPGroup();
        $group->setName("Test");
        $group->setRuleSet($ruleSet);

        $this->_entityManager->persist($group);
        $this->_entityManager->flush();
    }

    protected function characterCreation(RuleSet $ruleSet) : void
    {
        $character = new CharacterData();
        $character->setName("Darius");

        $ruleSet->addCharacterData($character);

        $this->_entityManager->persist($character);
        $this->_entityManager->flush();

        $statCategory = new CharacterStatCategory();
        $statCategory->setName("Skills");
        $statCategory->setStatsRequired(-1);

        $ruleSet->addCharacterStatCategory($statCategory);

        $this->_entityManager->persist($statCategory);
        $this->_entityManager->flush();

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
        $class->setLowestLevel(1);
        $class->setHighestLevel(20);
        $ruleSet->addCharacterClass($class);

        $this->_entityManager->persist($class);
        $this->_entityManager->flush();

        $classLevel = new CharacterClassLevel();
        $class->addCharacterClassLevel($classLevel);
        $character->addCharacterClassLevel($classLevel);
        $classLevel->setLevel(0);

        $this->_entityManager->persist($classLevel);
        $this->_entityManager->flush();
    }
}