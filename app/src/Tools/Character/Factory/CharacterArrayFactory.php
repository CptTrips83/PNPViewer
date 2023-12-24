<?php

namespace App\Tools\Character\Factory;

use App\Entity\CharacterData;
use App\Entity\RuleSet;
use App\Tools\Character\Interfaces\CharacterBuilderInterface;
use App\Tools\Character\Interfaces\CharacterArrayStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;

class CharacterArrayFactory
{
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