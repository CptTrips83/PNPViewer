<?php

namespace App\Tools\Character\Interfaces;

use App\Entity\RuleSet;

interface CharacterCreationInterface
{
    public function create(array $data) : void;

    /**
     * @return array Gibt ein Array zurück, dass die Stats enthält. Gruppiert nach Kategorie
     * ID, Name, Description
     */
    public function getStats() : array;

}