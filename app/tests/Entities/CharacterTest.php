<?php

namespace App\Tests\Entities;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatsCategory;
use App\Entity\CharacterStatValue;
use App\Entity\RuleSet;
use App\Tests\base\KernelTestSetup;
use App\Tools\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CharacterTest extends KernelTestSetup
{
    public function testCharacterCreation()
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

        $statValue = new CharacterStatValue();
        $statValue->setCharacterStat($stat);
        $statValue->setValue(1);

        $this->_entityManager->persist($statValue);
        $this->_entityManager->flush();

        $characterData = new CharacterData();
        $characterData->setName("Darius");
        $characterData->setRuleSet($ruleSet);
        $characterData->addCharacterStatValue($statValue);

        $this->_entityManager->persist($characterData);
        $this->_entityManager->flush();

        $repo = $this->_entityManager->getRepository(CharacterData::class);

        $data = $repo->findOneBy(["name" => "Darius"]);

        $repo = $this->_entityManager->getRepository(CharacterStat::class);

        $stats = $repo->findAll();

        $this->assertEquals("Stats", $data->getCharacterStatValue()[0]->getCharacterStat()->getCategory()->getName());
        $this->assertEquals("Intelligence", $data->getCharacterStatValue()[0]->getCharacterStat()->getName());
        $this->assertEquals(1, $data->getCharacterStatValue()[0]->getValue());
        $this->assertEquals("Darius", $data->getName());
        $this->assertCount(1, $stats);
    }
}