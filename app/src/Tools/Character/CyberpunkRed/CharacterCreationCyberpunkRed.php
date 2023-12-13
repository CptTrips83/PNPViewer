<?php

namespace App\Tools\Character\CyberpunkRed;

use App\Entity\CharacterClassValue;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatsCategory;
use App\Entity\CharacterStatValue;
use App\Entity\RuleSet;
use App\Entity\RuleSetClass;
use App\Tools\Character\Interfaces\CharacterCreationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CharacterCreationCyberpunkRed implements CharacterCreationInterface
{
    private EntityManagerInterface $_entityManager;
    private RuleSet $_ruleSet;

    public function __construct(
        EntityManagerInterface $entityManager,
        RuleSet $ruleSet
    )
    {
        $this->_entityManager = $entityManager;
        $this->_ruleSet = $ruleSet;
    }

    public function create(array $data): void
    {
        $character = new CharacterData();

        $character->setName($data["CharacterData"]["name"]);



        $character->setRuleSet($this->_ruleSet);

        $className = $data["CharacterData"]["class"]["name"];

        $repoStatCategories = $this->_entityManager->getRepository(CharacterStatsCategory::class);
        $repoStat = $this->_entityManager->getRepository(CharacterStat::class);
        $repoClass = $this->_entityManager->getRepository(RuleSetClass::class);

        // Klasse abrufen
        $class = $repoClass->findOneBy([
            "name" => $className,
            "ruleSet" => $this->_ruleSet
        ]);

        // Klasse einfügen
        $classValue = new CharacterClassValue();
        $classValue->setRuleSetClass($class);
        $classValue->setValue(1);
        $character->addClassValue($classValue);
        $this->_entityManager->persist(($classValue));

        // Kategorien abrufen
        $categories = $repoStatCategories->findBy(["ruleSet" => $this->_ruleSet]);

        $this->_entityManager->persist($character);
        $this->_entityManager->flush();

        // Kategorien verarbeiten
        foreach ($categories as $category) {
            foreach ($data[$category->getName()] as $statData)
            {
                try{
                    $stat = $repoStat->findOneBy(["id" => $statData["id"]]);
                    $skillValue = new CharacterStatValue();
                    $skillValue->setCharacterStat($stat);
                    $skillValue->setValue($statData["value"]);
                    $character->addStatValue($skillValue);
                    $this->_entityManager->persist($skillValue);

                } catch (\Exception $exception) {

                }
            }
        }

        $this->_entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getBlankData(): array
    {
        $result = array();

        $result["CharacterData"]["name"] = "";
        $result["CharacterData"]["class"] = array();

        $repoStatCategories = $this->_entityManager->getRepository(CharacterStatsCategory::class);
        $repoStat = $this->_entityManager->getRepository(CharacterStat::class);

        // Kategorien abrufen
        $categories = $repoStatCategories->findBy(["ruleSet" => $this->_ruleSet]);

        // Kategorien verarbeiten
        foreach ($categories as $category)
        {
            $result[$category->getName()] = array();

            // Stats abrufen
            $stats = $repoStat->findBy(["category" => $category]);

            // Stats verarbeiten
            foreach ($stats as $stat) {
                $result[$category->getName()][$stat->getName()]["id"] = $stat->getId();
                $result[$category->getName()][$stat->getName()]["name"] = $stat->getName();
                $result[$category->getName()][$stat->getName()]["description"] = $stat->getDescription();
                $result[$category->getName()][$stat->getName()]["value"] = 0;
            }
        }

        return $result;
    }
}