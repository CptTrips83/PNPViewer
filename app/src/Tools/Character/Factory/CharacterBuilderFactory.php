<?php

namespace App\Tools\Character\Factory;

use App\Entity\RuleSet;
use App\Tools\Character\Interfaces\CharacterBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CharacterBuilderFactory
{

    public static function get(
        EntityManagerInterface $entityManager,
        RuleSet $ruleSet
    ) : CharacterBuilderInterface | null {
        $result = null;
        $className = $ruleSet->getCharacterBuilderName();
        try {
            $result = new $className(
                $entityManager,
                $ruleSet
            );
        } catch (Exception $ex) {

        }
        return $result;
    }
}