<?php

namespace App\Tools\Character\CyberpunkRed;

use App\Entity\CharacterData;
use App\Entity\CharacterStatCategory;
use App\Tools\Character\Interfaces\CharacterArrayStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CyberpunkCharacterArrayStrategy implements CharacterArrayStrategyInterface
{
    public function __construct()
    {

    }
    // TODO Min/Max Values in Json einfügen
    public function generateJSON(CharacterData $character): array
    {
        $result = array();

        $result['data']['id'] = $character->getId();
        $result['data']['name'] = $character->getName();
        $result['data']['handle'] = $character->getNickname();
        $result['data']['pnpGroup'] = $character->getPnpGroup();
        $result['data']['ruleSet'] = $character->getRuleSet();

        foreach ($character->getCharacterClassLevels() as $classLevel)
        {
            $result['classes'][$classLevel->getCharacterClass()->getName()]['classData'] = $classLevel->getCharacterClass();
            $result['classes'][$classLevel->getCharacterClass()->getName()]['level'] = $classLevel->getLevel();
        }

        $arrayDetails = array();
        $arraySkills = array();
        $arrayPerks = array();

        foreach ($character->getCharacterStatValues() as $statValue)
        {
            if($statValue->getCharacterStat()->getCategory()->getStatsRequired() == 1) {

                $arrayDetails
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    ['category'] = $statValue->getCharacterStat()->getCategory();
                $arrayDetails
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    ['statData'] = $statValue->getCharacterStat();
                $arrayDetails
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    ['value'] = $statValue->getValue();
            }

            if($statValue->getCharacterStat()->getCategory()->getStatsRequired() == -1 &&
                $statValue->getCharacterStat()->getCategory()->getName() == "skills") {

                $arraySkills
                    [$statValue->getCharacterStat()->getName()]['category'] = $statValue->getCharacterStat()->getCategory();
                $arraySkills
                    [$statValue->getCharacterStat()->getName()]['statData'] = $statValue->getCharacterStat();
                $arraySkills
                    [$statValue->getCharacterStat()->getName()]['value'] = $statValue->getValue();
            } else if($statValue->getCharacterStat()->getCategory()->getStatsRequired() == -1 &&
                $statValue->getCharacterStat()->getCategory()->getName() != "skills") {

                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]['category'] = $statValue->getCharacterStat()->getCategory();
                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]['statData'] = $statValue->getCharacterStat();
                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]['value'] = $statValue->getValue();
            }
        }

        $result['details'] = $arrayDetails;
        $result['skills'] = $arraySkills;
        $result['perks'] = $arrayPerks;

        return ($result);
    }
}