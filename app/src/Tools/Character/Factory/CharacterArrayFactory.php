<?php

namespace App\Tools\Character\Factory;

use App\Entity\CharacterData;
use App\Entity\RuleSet;
use App\Tools\Character\Interfaces\CharacterBuilderInterface;
use App\Tools\Character\Interfaces\CharacterArrayStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * CharacterArrayFactory class.
 *
 * This class is responsible for creating instances of classes that implement the CharacterArrayStrategyInterface.
 * It provides a static method `get()` that takes a RuleSet object as a parameter and returns an instance of a class
 * that implements the CharacterArrayStrategyInterface based on the rule set.
 *
 */
class CharacterArrayFactory
{
    /**
     * Retrieves a CharacterArrayStrategyInterface object based on the provided RuleSet.
     *
     * @param RuleSet $ruleSet The RuleSet object used to determine the CharacterArrayStrategyInterface object.
     *
     * @return CharacterArrayStrategyInterface|null The retrieved CharacterArrayStrategyInterface object, or null if an exception occurred.
     */
    public static function get(
        RuleSet $ruleSet
    ) : CharacterArrayStrategyInterface | null {
        $result = null;
        $className = $ruleSet->getCharacterArrayName();
        try {
            $result = new $className();
        } catch (\Exception $ex) {

        }
        return $result;
    }
}