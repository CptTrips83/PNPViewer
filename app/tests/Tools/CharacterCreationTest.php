<?php

namespace App\Tests\Tools;

use App\Entity\CharacterStat;
use App\Entity\CharacterStatsCategory;
use App\Entity\RuleSet;
use App\Tests\base\KernelTestSetup;
use App\Tools\CharacterCreation\CyberpunkRed\CharacterCreationCyberpunkRed;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CharacterCreationTest extends KernelTestSetup
{
    public function testGetStats()
    {
        $ruleSet = new RuleSet();
        $ruleSet->setName("Cyberpunk RED");
        $this->_entityManager->persist($ruleSet);
        $this->_entityManager->flush();

        $categoryStats = new CharacterStatsCategory();
        $categoryStats->setName("Stats");
        $categoryStats->setRuleSet($ruleSet);
        $this->_entityManager->persist($categoryStats);
        $this->_entityManager->flush();

        $stat = new CharacterStat();
        $stat->setCategory($categoryStats);
        $stat->setName("Intelligence");

        $this->_entityManager->persist($stat);
        $this->_entityManager->flush();

        $stat = new CharacterStat();
        $stat->setCategory($categoryStats);
        $stat->setName("Reflex");

        $this->_entityManager->persist($stat);
        $this->_entityManager->flush();

        $categoryStats = new CharacterStatsCategory();
        $categoryStats->setName("Skills");
        $categoryStats->setRuleSet($ruleSet);
        $this->_entityManager->persist($categoryStats);
        $this->_entityManager->flush();

        $stat = new CharacterStat();
        $stat->setCategory($categoryStats);
        $stat->setName("Hacking");

        $this->_entityManager->persist($stat);
        $this->_entityManager->flush();

        $creator = new CharacterCreationCyberpunkRed($this->_entityManager);
        $data = $creator->getStats($ruleSet);

        $this->assertEquals("1", $data["Stats"]["Intelligence"]["id"]);
        $this->assertEquals("2", $data["Stats"]["Reflex"]["id"]);
        $this->assertEquals("3", $data["Skills"]["Hacking"]["id"]);
    }
}