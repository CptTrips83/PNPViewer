<?php

namespace App\Tools\Character\Interfaces;

use App\Entity\CharacterClass;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\RuleSet;

interface CharacterEditorInterface
{

    /**
     * Setzt einen bestehenden Character im Builder
     * @param CharacterData $character Der Character der gesetzt werden soll
     * @return CharacterEditorInterface
     */
    public function setCharacter(CharacterData $character) : CharacterEditorInterface;

    /**
     * Speichert ein Proberty
     * @param string $property Name des Properties
     * @param mixed $value Wert des Proberty
     * @return CharacterEditorInterface
     */
    public function set(string $property, mixed $value) : CharacterEditorInterface;
    /**
     * Ändert den Level einer Klasse
     * @param CharacterClass $class Die Klasse für den der Level geändert werden soll
     * @param int $level Der Level den die Klasse hat
     * @return CharacterEditorInterface
     */
    public function setClassLevel(CharacterClass $class, int $level) : CharacterEditorInterface;
    /**
     * Ändert den Value eines Stats
     * @param CharacterStat $stat Der Stat für den der Value geändert werden soll
     * @param int $value Der Value den der Stat haben soll
     * @return CharacterEditorInterface
     */
    public function setStatValue(CharacterStat $stat, int $value) : CharacterEditorInterface;
    /**
     * Speichert den Character
     * @return CharacterData Der fertige Character
     */
    public function saveCharacter() : CharacterData;
}