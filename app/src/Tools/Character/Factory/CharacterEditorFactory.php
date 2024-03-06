<?php

namespace App\Tools\Character\Factory;

use App\Entity\RuleSet;
use App\Tools\Character\Interfaces\CharacterEditorInterface;
use Doctrine\ORM\EntityManagerInterface;

class CharacterEditorFactory
{

    public static function get(
        EntityManagerInterface $entityManager,
        RuleSet $ruleSet
    ) : CharacterEditorInterface | null {
        $result = null;
        $className = $ruleSet->getCharacterEditorName();
        try {
            $result = new $className(
                $entityManager,
                $ruleSet
            );
        } catch (\Exception $ex) {

        }
        return $result;
    }
}