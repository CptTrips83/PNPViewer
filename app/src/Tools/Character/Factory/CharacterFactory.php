<?php

namespace App\Tools\Character\Factory;

use App\Entity\CharacterData;
use App\Entity\RuleSet;
use App\Tools\Character\Interfaces\CharacterBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;

class CharacterFactory
{

    public static function get(RuleSet $ruleSet = null) : CharacterData | null
    {
        $result = null;
        try {
            $result = new CharacterData();
        } catch (\Exception $ex) {
        }
        return $result;
    }
}
