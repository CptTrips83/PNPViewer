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
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CyberpunkCharacterBuilder
 *
 * This class is responsible for building a Cyberpunk character. It implements the CharacterBuilderInterface interface.
 */
class CyberpunkCharacterBuilder implements CharacterBuilderInterface
{
    private CharacterData $_character;

    /**
     * Constructor for the class.
     *
     * @param EntityManagerInterface $_entityManager The entity manager interface.
     */
    public function __construct(
        private readonly EntityManagerInterface $_entityManager
    )
    {

    }

    /**
     * Creates a character using the specified rule set.
     *
     * @param RuleSet $ruleSet The rule set used for character creation.
     *
     * @return CharacterBuilderInterface The character builder interface instance.
     */
    public function createCharacter(RuleSet $ruleSet): CharacterBuilderInterface
    {
        $this->_character = CharacterFactory::get();
        $this->_character->setCreationStart(new DateTime());
        $this->_character->setRuleSet($ruleSet);
        $this->_character->setName("");
        $this->_entityManager->persist($this->_character);

        return $this;
    }

    /**
     * Setter method for setting the character data.
     *
     * @param CharacterData $character The character data that needs to be set.
     * @return CharacterBuilderInterface Returns an instance of the CharacterBuilderInterface.
     */
    public function setCharacter(CharacterData $character): CharacterBuilderInterface
    {
        $this->_character = $character;

        return $this;
    }

    /**
     * Set a property value for the character object.
     *
     * @param string $property The name of the property to be set.
     * @param mixed $value The value to set for the property.
     * @return CharacterBuilderInterface Returns the instance of the CharacterBuilderInterface.
     */
    public function set(string $property, mixed $value) : CharacterBuilderInterface
    {
        $setter = "set".ucfirst($property);

        if(method_exists($this->_character, $setter))
        {
            $this->_character->$setter($value);
        }

        return $this;
    }

    /**
     * Sets the name of the character.
     *
     * @param string $name The name of the character.
     * @return CharacterBuilderInterface Returns the character builder interface object.
     */
    public function setName(string $name): CharacterBuilderInterface
    {
        $this->_character->setName($name);
        return $this;
    }

    /**
     * Assigns a character class to the character builder.
     *
     * @param CharacterClass $class The character class object to be assigned.
     * @param int $level The level at which the character class is assigned.
     * @return CharacterBuilderInterface Returns the current instance of CharacterBuilderInterface.
     */
    public function addClass(CharacterClass $class, int $level): CharacterBuilderInterface
    {
        $classLevel = new CharacterClassLevel();
        $class->addCharacterClassLevel($classLevel);
        $classLevel->setLevel($level);
        $this->_character->addCharacterClassLevel($classLevel);

        $this->_entityManager->persist($classLevel);

        return $this;
    }

    /**
     * Adds a new character stat to the character.
     *
     * @param CharacterStat $stat The character stat object.
     * @param int $value The value of the character stat.
     * @return CharacterBuilderInterface The character builder interface.
     */
    public function addStat(CharacterStat $stat, int $value): CharacterBuilderInterface
    {
        $statValue = new CharacterStatValue();

        if($stat->getCategory()->getStatsRequired() == 1 ||
            $stat->getCategory()->getStatsRequired() == -1) {
            $repo = $this->_entityManager->getRepository(CharacterStatValue::class);

            $statValueTemp = $repo->findOneBy([
                'characterData' => $this->_character,
                'characterStat' => $stat
            ]);

            if($statValueTemp != null) $statValue = $statValueTemp;
        }
        if($statValue->getValue() == null) {
            $stat->addCharacterStatValue($statValue);
        }

        $statValue->setValue($value);
        $this->_character->addCharacterStatValue($statValue);

        $this->_entityManager->persist($statValue);
        return $this;
    }

    /**
     * Build the character and persist it in the database.
     *
     * @return CharacterData The built character.
     */
    public function buildCharacter(): CharacterData
    {
        $this->_entityManager->flush();

        return $this->_character;
    }

    /**
     * Finish the creation process.
     *
     * @return CharacterBuilderInterface The character builder interface.
     */
    public function finishCreation(): CharacterBuilderInterface
    {
        $this->_character->setCreationEnd(new DateTime());
        return $this;
    }
}