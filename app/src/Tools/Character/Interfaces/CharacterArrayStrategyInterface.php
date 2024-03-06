<?php

namespace App\Tools\Character\Interfaces;

use App\Entity\CharacterData;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Interface CharacterArrayStrategyInterface
 *
 * This interface defines the contract for a class that generates a JSON representation
 * of a CharacterData object.
 */
interface CharacterArrayStrategyInterface
{
    /**
     * Generates a JSON representation of a CharacterData object.
     *
     * @param CharacterData $character The CharacterData object to generate JSON for.
     *
     * @return array The JSON representation of the CharacterData object.
     */
    public function generateJSON(CharacterData $character) : array;
}