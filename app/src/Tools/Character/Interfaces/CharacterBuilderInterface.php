<?php

namespace App\Tools\Character\Interfaces;

use App\Entity\CharacterClass;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\RuleSet;

interface CharacterBuilderInterface
{

    public function createCharacter(RuleSet $ruleSet) : CharacterBuilderInterface;

    /**
     * Setzt einen bestehenden Character im Builder
     * @param CharacterData $character Der Character der gesetzt werden soll
     * @return CharacterBuilderInterface
     */
    public function setCharacter(CharacterData $character) : CharacterBuilderInterface;

    /**
     * Speichert ein Proberty
     * @param string $property Name des Properties
     * @param mixed $value Wert des Proberty
     * @return CharacterBuilderInterface
     */
    public function set(string $property, mixed $value) : CharacterBuilderInterface;
    /**
     * Speichert den Namen des Characters
     * @param string $name Name des Characters
     * @return CharacterBuilderInterface
     */
    public function setName(string $name) : CharacterBuilderInterface;
    /**
     * Fügt eine Klasse mit dem angegebenen Level ein
     * @param CharacterClass $class Die Klasse die eingefügt werden soll
     * @param int $level Der Level den die Klasse hat
     * @return CharacterBuilderInterface
     */
    public function addClass(CharacterClass $class, int $level) : CharacterBuilderInterface;
    /**
     * Fügt einen Stat mit dem angegebenen Value ein
     * @param CharacterStat $stat Der Stat der eingefügt werden soll
     * @param int $value Der Value den der Stat haben soll
     * @return CharacterBuilderInterface
     */
    public function addStat(CharacterStat $stat, int $value) : CharacterBuilderInterface;

    /**
     * Setzt das Datum für das Ende der Character Creation ind der DB
     * @return CharacterBuilderInterface
     */
    public function finishCreation() : CharacterBuilderInterface;
}
