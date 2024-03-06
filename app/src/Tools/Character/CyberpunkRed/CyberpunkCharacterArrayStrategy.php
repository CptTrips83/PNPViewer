<?php

namespace App\Tools\Character\CyberpunkRed;

use App\Entity\CharacterData;
use App\Entity\CharacterStatCategory;
use App\Entity\CharacterStatValue;
use App\Tools\Character\Interfaces\CharacterArrayStrategyInterface;
use Exception;

/**
 * Class CyberpunkCharacterArrayStrategy
 *
 * This class implements the CharacterArrayStrategyInterface and provides a method to generate JSON representation
 * of a Cyberpunk character.
 */
class CyberpunkCharacterArrayStrategy implements CharacterArrayStrategyInterface
{

    /**
     * Constant representing the key for the data array.
     *
     * @var string ARRAY_NAME_DATA
     */
    private const ARRAY_NAME_DATA = "data";
    /**
     * The name of the constant representing the array key for classes.
     *
     * @var string ARRAY_NAME_CLASSES
     */
    private const ARRAY_NAME_CLASSES = "classes";
    /**
     * Constant that represents the key name for the "skills" array.
     *
     * @var string ARRAY_NAME_SKILL
     */
    private const ARRAY_NAME_SKILL = "skills";
    /**
     * Constant that represents the key name for the "details" array.
     *
     * @var string ARRAY_NAME_DETAILS
     */
    private const ARRAY_NAME_DETAILS = "details";
    /**
     * Constant that represents the key name for the "perks" array.
     *
     * @var string ARRAY_NAME_PERKS
     */
    private const ARRAY_NAME_PERKS = "perks";

    /**
     * @inheritDoc
     * @throws Exception If $character is null.
     */
    public function generateJSON(CharacterData | null $character): array
    {
        if($character === null)  throw new Exception('CharacterData is null in CyberpunkCharacterArrayStrategy::generateJSON');

        $result = array();

        try {

            $result[self::ARRAY_NAME_DATA]['id'] = $character->getId();
            $result[self::ARRAY_NAME_DATA]['name'] = $character->getName();
            $result[self::ARRAY_NAME_DATA]['handle'] = $character->getNickname();
            $result[self::ARRAY_NAME_DATA]['pnpGroup'] = $character->getPnpGroup();
            $result[self::ARRAY_NAME_DATA]['ruleSet'] = $character->getRuleSet();

            foreach ($character->getCharacterClassLevels() as $classLevel) {
                $result[self::ARRAY_NAME_CLASSES][$classLevel->getCharacterClass()->getName()]['valueId'] = $classLevel->getId();
                $result[self::ARRAY_NAME_CLASSES][$classLevel->getCharacterClass()->getName()]['lowestLevel'] = $classLevel->getCharacterClass()->getLowestLevel();
                $result[self::ARRAY_NAME_CLASSES][$classLevel->getCharacterClass()->getName()]['highestLevel'] = $classLevel->getCharacterClass()->getHighestLevel();
                $result[self::ARRAY_NAME_CLASSES][$classLevel->getCharacterClass()->getName()]['classData'] = $classLevel->getCharacterClass();
                $result[self::ARRAY_NAME_CLASSES][$classLevel->getCharacterClass()->getName()]['level'] = $classLevel->getLevel();
            }

            $arrayDetails = array();
            $arraySkills = array();
            $arrayPerks = array();

            foreach ($character->getCharacterStatValues() as $statValue) {

                /** @var CharacterStatCategory $characterCategory */
                $characterCategory = $statValue->getCharacterStat()->getCategory();

                if($characterCategory->getName() == "characterData"){
                    $result[self::ARRAY_NAME_DATA][$statValue->getCharacterStat()->getName()]['value'] = $statValue->getValue();
                    $result[self::ARRAY_NAME_DATA][$statValue->getCharacterStat()->getName()]['valueId'] = $statValue->getId();
                    continue;
                }

                if ($characterCategory->getStatsRequired() == 1) {
                    $arrayDetails[$characterCategory->getName()] = $this->assignValues($statValue);
                }

                if ($characterCategory->getStatsRequired() == -1 &&
                    $characterCategory->getName() == self::ARRAY_NAME_SKILL) {
                    $arraySkills
                    [$statValue->getCharacterStat()->getName()] = $this->assignValues($statValue);
                } else if ($characterCategory->getStatsRequired() == -1 &&
                    $characterCategory->getName() != self::ARRAY_NAME_SKILL) {
                    $arrayPerks
                    [$characterCategory->getName()]
                    [$statValue->getCharacterStat()->getName()] = $this->assignValues($statValue);
                }
            }

            $result[self::ARRAY_NAME_DETAILS] = $arrayDetails;
            $result[self::ARRAY_NAME_SKILL] = $arraySkills;
            $result[self::ARRAY_NAME_PERKS] = $arrayPerks;
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            throw $exception;
        }

        return ($result);
    }

    /**
     * Assigns values from a CharacterStatValue object to an array.
     *
     * @param CharacterStatValue|null $sourceObject The CharacterStatValue object to extract values from.
     * @return array The array with assigned values.
     * @throws Exception If $sourceObject is null.
     */
    private function assignValues(CharacterStatValue | null $sourceObject) : array
    {
        if($sourceObject === null) throw new Exception('CharacterStatValue is null in CyberpunkCharacterArrayStrategy::assignValues');

        $targetArray = array();

        $targetArray['valueId'] = $sourceObject->getId();
        $targetArray['lowestValue'] = $sourceObject->getCharacterStat()->getLowestValue();
        $targetArray['highestValue'] = $sourceObject->getCharacterStat()->getHighestValue();
        $targetArray['category'] = $sourceObject->getCharacterStat()->getCategory();
        $targetArray['statData'] = $sourceObject->getCharacterStat();
        $targetArray['value'] = $sourceObject->getValue();

        return $targetArray;
    }
}