<?php

namespace App\Tools\Character\CyberpunkRed;

use App\Entity\CharacterClass;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\RuleSet;
use App\Tools\Character\Factory\CharacterFactory;
use App\Tools\Character\Interfaces\CharacterEditorInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CyberpunkCharacterEditor
 *
 * This class implements the CharacterEditorInterface and provides methods to edit a cyberpunk character.
 */
class CyberpunkCharacterEditor implements CharacterEditorInterface
{
    private CharacterData $_character;


    /**
     * Constructs a new instance of the class.
     *
     * @param EntityManagerInterface $_entityManager The entity manager interface.
     * @param RuleSet $ruleSet The rule set object.
     */
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

    /**
     * Sets the character for the editor.
     *
     * @param CharacterData $character The character object to set.
     * @return CharacterEditorInterface Returns the instance of the character editor interface.
     */
    public function setCharacter(CharacterData $character): CharacterEditorInterface
    {
        $this->_character = $character;
        return $this;
    }

    /**
     * Sets a property of the character object.
     *
     * @param string $property The name of the property to be set.
     * @param mixed $value The value to be set for the property.
     * @return CharacterEditorInterface Returns the CharacterEditorInterface object.
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

    /**
     * Sets the level of a character class for the given character.
     *
     * @param CharacterClass $class The character class to set the level for.
     * @param int $level The level to set for the character class.
     * @return CharacterEditorInterface The instance of the CharacterEditorInterface.
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

    /**
     * Sets the value of a character stat for the given character.
     *
     * @param CharacterStat $stat The character stat to set the value for.
     * @param int $value The value to set for the character stat.
     * @return CharacterEditorInterface The instance of the CharacterEditorInterface.
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


    /**
     * Save the character data to the database.
     *
     * @return CharacterData The updated character data.
     */
    public function saveCharacter(): CharacterData
    {
        $this->_entityManager->flush();
        return $this->_character;
    }
}