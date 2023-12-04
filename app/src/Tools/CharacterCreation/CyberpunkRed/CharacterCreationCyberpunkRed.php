<?php

namespace App\Tools\CharacterCreation\CyberpunkRed;

use App\Entity\CharacterStat;
use App\Entity\CharacterStatsCategory;
use App\Entity\RuleSet;
use App\Tools\CharacterCreation\Interfaces\CharacterCreationInterface;
use Doctrine\ORM\EntityManagerInterface;

class CharacterCreationCyberpunkRed implements CharacterCreationInterface
{
    private EntityManagerInterface $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->_entityManager = $entityManager;
    }

    public function create(array $data): void
    {
        // TODO: Implement create() method.
    }

    /**
     * @inheritDoc
     */
    public function getStats(RuleSet $ruleSet): array
    {
        $result = array();

        $repoStatCategories = $this->_entityManager->getRepository(CharacterStatsCategory::class);
        $repoStat = $this->_entityManager->getRepository(CharacterStat::class);

        // Kategorien abrufen
        $categories = $repoStatCategories->findBy(["ruleSet" => $ruleSet]);

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
            }
        }

        return $result;
    }
}