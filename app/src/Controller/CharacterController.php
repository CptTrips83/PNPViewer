<?php

namespace App\Controller;

use App\Entity\CharacterData;
use App\Tools\Character\CyberpunkRed\CyberpunkCharacterArrayStrategy;
use App\Tools\Character\Factory\CharacterArrayFactory;
use App\Traits\ControllerEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController extends AbstractController
{
    use ControllerEntityManager;

    #[Route('/character/list', name: 'app_character_list')]
    public function list(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->loadEntityManager($entityManager);

        return $this->render('character/show.html.twig', [
            'controller_name' => 'CharacterController',
        ]);
    }

    #[Route('/character/show/details/{characterId}', name: 'app_character_show_details')]
    public function details(
        Request $request,
        int $characterId,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->loadEntityManager($entityManager);

        $repoCharacter = $entityManager->getRepository(CharacterData::class);

        $character = $repoCharacter->find($characterId);

        $characterJSON = CharacterArrayFactory::get($character->getRuleSet());

        $json = $characterJSON->generateJSON($character);

        return $this->render('character/details.html.twig', [
            'data' => $json,
        ]);
    }

    #[Route('/character/show/details/JSON/{characterId}', name: 'app_character_show_json')]
    public function jsonData(
        Request $request,
        int $characterId,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->loadEntityManager($entityManager);

        $repoCharacter = $entityManager->getRepository(CharacterData::class);

        $character = $repoCharacter->find($characterId);

        $characterJSON = CharacterArrayFactory::get($character->getRuleSet());

        $json = $characterJSON->generateJSON($character);

        return new JsonResponse($json);
    }
}
