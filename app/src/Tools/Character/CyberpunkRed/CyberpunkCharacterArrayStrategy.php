<?php

namespace App\Tools\Character\CyberpunkRed;

use App\Entity\CharacterData;
use App\Tools\Character\Interfaces\CharacterArrayStrategyInterface;

/**
 * Class CyberpunkCharacterArrayStrategy
 *
 * This class implements the CharacterArrayStrategyInterface and provides a method to generate JSON representation
 * of a Cyberpunk character.
 */
class CyberpunkCharacterArrayStrategy implements CharacterArrayStrategyInterface
{
    public function __construct()
    {

    }

    /**
     * @inheritDoc
     */
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
            $result['classes'][$classLevel->getCharacterClass()->getName()]['valueId'] = $classLevel->getId();
            $result['classes'][$classLevel->getCharacterClass()->getName()]['lowestLevel'] = $classLevel->getCharacterClass()->getLowestLevel();
            $result['classes'][$classLevel->getCharacterClass()->getName()]['highestLevel'] = $classLevel->getCharacterClass()->getHighestLevel();
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
                    ['valueId'] = $statValue->getId();
                $arrayDetails
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    ['lowestValue'] = $statValue->getCharacterStat()->getLowestValue();
                $arrayDetails
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    ['highestValue'] = $statValue->getCharacterStat()->getHighestValue();
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
                    [$statValue->getCharacterStat()->getName()]
                    ['valueId'] = $statValue->getId();
                $arraySkills
                    [$statValue->getCharacterStat()->getName()]
                    ['lowestValue'] = $statValue->getCharacterStat()->getLowestValue();
                $arraySkills
                    [$statValue->getCharacterStat()->getName()]
                    ['highestValue'] = $statValue->getCharacterStat()->getHighestValue();
                $arraySkills
                    [$statValue->getCharacterStat()->getName()]
                    ['category'] = $statValue->getCharacterStat()->getCategory();
                $arraySkills
                    [$statValue->getCharacterStat()->getName()]
                    ['statData'] = $statValue->getCharacterStat();
                $arraySkills
                    [$statValue->getCharacterStat()->getName()]
                    ['value'] = $statValue->getValue();
            } else if($statValue->getCharacterStat()->getCategory()->getStatsRequired() == -1 &&
                $statValue->getCharacterStat()->getCategory()->getName() != "skills") {

                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]
                    ['valueId'] = $statValue->getId();
                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]
                    ['lowestValue'] = $statValue->getCharacterStat()->getLowestValue();
                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]
                    ['highestValue'] = $statValue->getCharacterStat()->getHighestValue();
                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]
                    ['category'] = $statValue->getCharacterStat()->getCategory();
                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]
                    ['statData'] = $statValue->getCharacterStat();
                $arrayPerks
                    [$statValue->getCharacterStat()->getCategory()->getName()]
                    [$statValue->getCharacterStat()->getName()]
                    ['value'] = $statValue->getValue();
            }
        }

        $result['details'] = $arrayDetails;
        $result['skills'] = $arraySkills;
        $result['perks'] = $arrayPerks;

        return ($result);
    }
}