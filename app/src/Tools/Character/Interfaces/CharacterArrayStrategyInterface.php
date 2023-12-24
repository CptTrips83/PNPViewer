<?php

namespace App\Tools\Character\Interfaces;

use App\Entity\CharacterData;
use Symfony\Component\HttpFoundation\JsonResponse;

interface CharacterArrayStrategyInterface
{
    public function generateJSON(CharacterData $character) : array;
}