<?php

namespace App\Tests\Tools;

use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatsCategory;
use App\Entity\RuleSet;
use App\Tests\base\KernelTestSetup;
use App\Tools\Character\CyberpunkRed\CharacterCreationCyberpunkRed;

class CharacterCreationTest extends KernelTestSetup
{
    public function testGetStats()
    {
        $data = $this->getStats();

        $this->assertEquals("1", $data["Stats"]["Intelligence"]["id"]);
        $this->assertEquals("2", $data["Stats"]["Reflex"]["id"]);
        $this->assertEquals("3", $data["Skills"]["Hacking"]["id"]);
    }

    public function testCreateCharacter()
    {
        $ruleSet = new RuleSet();
        $ruleSet->setName("Cyberpunk RED");
        $this->_entityManager->persist($ruleSet);
        $this->_entityManager->flush();

        $data = $this->generateCreationArray($this->getStats());

        $creator = new CharacterCreationCyberpunkRed(
            $this->_entityManager,
            $ruleSet
        );

        $creator->create($data);

        $repoCharacter = $this->_entityManager->getRepository(CharacterData::class);
        $character = $repoCharacter->findOneBy(["name" => "Darius"]);

        $this->assertIsObject($character);
        $this->assertEquals("1", $data["Stats"]["Intelligence"]["value"]);
        $this->assertEquals("1", $data["Stats"]["Reflex"]["value"]);
        $this->assertEquals("1", $data["Skills"]["Hacking"]["value"]);
    }

    private function generateCreationArray(array $data) : array
    {
        $data["CharacterData"]["name"] = "Darius";

        foreach ($data as $key1 => $value)
        {
            foreach ($value as $key2 => $item)
            {
                if(isset($item["value"])) $data[$key1][$key2]["value"] = 1;
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    private function getStats(): array
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

        $creator = new CharacterCreationCyberpunkRed(
            $this->_entityManager,
            $ruleSet);
        $data = $creator->getStats($ruleSet);
        return $data;
    }
}