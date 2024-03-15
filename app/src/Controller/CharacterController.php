<?php

namespace App\Controller;

use App\Entity\CharacterData;
use App\Entity\PNPUser;
use App\Tools\Character\Factory\CharacterArrayFactory;
use App\Traits\ControllerEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController extends AbstractController
{
    use ControllerEntityManager;

    #[Route('/character/list', name: 'app_character_list')]
    public function list(
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->setEntityManager($entityManager);

        $characters = $this->getAvailableCharacters();

        return $this->render('character/list.html.twig', [
            'controller_name' => 'CharacterController',
            'characters' => $characters
        ]);
    }

    #[Route('/character/show/details/{characterId}', name: 'app_character_show_details')]
    public function details(
        int $characterId,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->setEntityManager($entityManager);

        $repoCharacter = $entityManager->getRepository(CharacterData::class);

        $character = $repoCharacter->find($characterId);

        if($character->getCreationEnd() == null) {
            return $this->redirectToRoute('app_character_creation_details', [
                'characterId' => $character->getId()
            ]);
        }

        $characterJSON = CharacterArrayFactory::get($character->getRuleSet());

        $json = $characterJSON->generateJSON($character);

        $isCharacterEditable = $this->isCharacterEditable($character);

        return $this->render('character/details.html.twig', [
            'data' => $json,
            'isCharacterEditable' => $isCharacterEditable
        ]);
    }

    #[Route('/character/show/details/JSON/{characterId}', name: 'app_character_show_json')]
    public function jsonData(
        int $characterId,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->setEntityManager($entityManager);

        $repoCharacter = $entityManager->getRepository(CharacterData::class);

        $character = $repoCharacter->find($characterId);

        $characterJSON = CharacterArrayFactory::get($character->getRuleSet());

        $json = $characterJSON->generateJSON($character);

        return new JsonResponse($json);
    }

    private function isCharacterEditable(CharacterData $character) : bool
    {
        $repoUser = $this->_entityManager->getRepository(PNPUser::class);

        $user = $repoUser->findOneBy([
            'username' => $this->getUser()->getUserIdentifier()
        ]);

        $pnpGroup = $character->getPnpGroup();

        try {
            if ($user == $character->getUser()) return true;
            if ($pnpGroup) {
                if ($user == $pnpGroup->getGameMaster()) return true;
            }
        } catch(Exception) {
            return false;
        }

        return false;
    }

    private function getAvailableCharacters() : array
    {
        $repoUser = $this->_entityManager->getRepository(PNPUser::class);

        $user = $repoUser->findOneBy([
            'username' => $this->getUser()->getUserIdentifier()
        ]);

        $result = $user->getCharacters()->toArray();

        $gameMasterGroups = $user->getGameMasterGroups();

        foreach ($gameMasterGroups as $gameMasterGroup) {
            $result = array_merge($result, $gameMasterGroup->getCharacters()->toArray());
        }

        return array_unique($result, SORT_REGULAR);
    }
}
