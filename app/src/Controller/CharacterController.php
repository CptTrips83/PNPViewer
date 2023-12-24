<?php

namespace App\Controller;

use App\Entity\CharacterData;
use App\Tools\Character\CyberpunkRed\CyberpunkCharacterArrayStrategy;
use App\Tools\Character\Factory\CharacterArrayFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController extends AbstractController
{
    #[Route('/show/character', name: 'app_character_show')]
    public function show(): Response
    {
        return $this->render('character/show.html.twig', [
            'controller_name' => 'CharacterController',
        ]);
    }

    #[Route('/show/character/details/{characterId}', name: 'app_character_show_details')]
    public function details(
        Request $request,
        int $characterId,
        EntityManagerInterface $entityManager
    ): Response
    {
        $repoCharacter = $entityManager->getRepository(CharacterData::class);

        $character = $repoCharacter->find($characterId);

        $characterJSON = CharacterArrayFactory::get($character->getRuleSet());

        $json = $characterJSON->generateJSON($character);

        return $this->render('character/details.html.twig', [
            'data' => $json,
        ]);
    }

    #[Route('/show/character/details/JSON/{characterId}', name: 'app_character_show_json')]
    public function jsonData(
        Request $request,
        int $characterId,
        EntityManagerInterface $entityManager
    ): Response
    {
        $repoCharacter = $entityManager->getRepository(CharacterData::class);

        $character = $repoCharacter->find($characterId);

        $characterJSON = CharacterArrayFactory::get($character->getRuleSet());

        $json = $characterJSON->generateJSON($character);

        return new JsonResponse($json);
    }
}
