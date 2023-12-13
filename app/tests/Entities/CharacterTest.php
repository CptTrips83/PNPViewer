<?php

namespace App\Tests\Entities;
use App\Entity\CharacterClassValue;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatsCategory;
use App\Entity\CharacterStatValue;
use App\Entity\RuleSet;
use App\Entity\RuleSetClass;
use App\Tests\base\KernelTestSetup;
use App\Tools\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CharacterTest extends KernelTestSetup
{
    public static function createData(EntityManagerInterface $entityManager) : void
    {
        $ruleSet = new RuleSet();
        $ruleSet->setName("Cyberpunk RED");
        $entityManager->persist($ruleSet);
        $entityManager->flush();

        $ruleSetClass = new RuleSetClass();
        $ruleSetClass->setName("Solo");
        $entityManager->persist($ruleSetClass);
        $entityManager->flush();

        $categoryStats = new CharacterStatsCategory();
        $categoryStats->setName("Stats");
        $categoryStats->setRuleSet($ruleSet);
        $entityManager->persist($categoryStats);
        $entityManager->flush();

        $stat = new CharacterStat();
        $stat->setCategory($categoryStats);
        $stat->setName("Intelligence");

        $entityManager->persist($stat);
        $entityManager->flush();

        $statValue = new CharacterStatValue();
        $statValue->setCharacterStat($stat);
        $statValue->setValue(1);

        $entityManager->persist($statValue);
        $entityManager->flush();

        $classValue = new CharacterClassValue();
        $classValue->setRuleSetClass($ruleSetClass);
        $classValue->setValue(1);

        $entityManager->persist($classValue);
        $entityManager->flush();

        $characterData = new CharacterData();
        $characterData->setName("Darius");
        $characterData->addClassValue($classValue);
        $characterData->setRuleSet($ruleSet);
        $characterData->addStatValue($statValue);

        $entityManager->persist($characterData);
        $entityManager->flush();
    }

    public function testCharacterCreation()
    {
        self::createData($this->_entityManager);

        $repo = $this->_entityManager->getRepository(CharacterData::class);

        $data = $repo->findOneBy(["name" => "Darius"]);

        $repo = $this->_entityManager->getRepository(CharacterStat::class);

        $stats = $repo->findAll();

        $this->assertEquals("Stats", $data->getStatValue()[0]->getCharacterStat()->getCategory()->getName());
        $this->assertEquals("Intelligence", $data->getStatValue()[0]->getCharacterStat()->getName());
        $this->assertEquals(1, $data->getStatValue()[0]->getValue());
        $this->assertEquals("Darius", $data->getName());
        $this->assertEquals(1, $data->getClassValue()[0]->getValue());
        $this->assertEquals("Solo", $data->getClassValue()[0]->getRuleSetClass()->getName());
        $this->assertCount(1, $stats);
    }
}