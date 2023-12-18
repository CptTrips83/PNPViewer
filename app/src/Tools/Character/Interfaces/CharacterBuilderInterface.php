<?php

namespace App\Tools\Character\Interfaces;

use App\Entity\CharacterClass;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\RuleSet;

interface CharacterBuilderInterface
{
    public function setName(string $name) : CharacterBuilderInterface;
    public function addClass(CharacterClass $class, int $level) : CharacterBuilderInterface;
    public function addStat(CharacterStat $stat, int $value) : CharacterBuilderInterface;
    public function buildCharacter() : CharacterData;
}