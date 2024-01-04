<?php

namespace App\Tools\Character\CyberpunkRed;

use App\Entity\CharacterClass;
use App\Entity\CharacterClassLevel;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatValue;
use App\Entity\RuleSet;
use App\Tools\Character\Factory\CharacterFactory;
use App\Tools\Character\Interfaces\CharacterBuilderInterface;
use App\Tools\Character\Interfaces\CharacterEditorInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CyberpunkCharacterEditor implements CharacterEditorInterface
{
    private CharacterData $_character;


    public function __construct(
        private readonly EntityManagerInterface $_entityManager,
        RuleSet $ruleSet
    )
    {
        $this->_character = CharacterFactory::get();
        $this->_character->setCreationStart(new DateTime());
        $this->_character->setRuleSet($ruleSet);
        $this->_character->setName("");
        $this->_entityManager->persist($this->_character);
    }
    /*
     * @inheritdoc
     */
    public function setCharacter(CharacterData $character): CharacterEditorInterface
    {
        $this->_character = $character;
        return $this;
    }

    /*
     * @inheritdoc
     */
    public function set(string $property, mixed $value) : CharacterEditorInterface
    {
        $setter = "set".ucfirst($property);

        if(method_exists($this->_character, $setter))
        {
            $this->_character->$setter($value);
        }

        return $this;
    }

    /*
     * @inheritdoc
     */
    public function setClassLevel(CharacterClass $class, int $level): CharacterEditorInterface
    {
        $classLevel = null;

        foreach ($this->_character->getCharacterClassLevels() as $data)
        {
            if($data->getCharacterClass() === $class) {
                $classLevel = $data;
                break;
            }
        }

        if($classLevel != null){
            $classLevel->setLevel($level);
            $this->_entityManager->persist($classLevel);
        }



        return $this;
    }

    /*
     * @inheritdoc
     */
    public function setStatValue(CharacterStat $stat, int $value): CharacterEditorInterface
    {
        $statValue = null;

        foreach ($this->_character->getCharacterStatValues() as $data)
        {
            if($data->getCharacterStat() === $stat) {
                $statValue = $data;
                break;
            }
        }

        if($statValue != null) {
            $statValue->setValue($value);
            $this->_entityManager->persist($statValue);
        }
        return $this;
    }

    /*
     * @inheritdoc
     */
    public function saveCharacter(): CharacterData
    {
        $this->_entityManager->flush();
        return $this->_character;
    }
}