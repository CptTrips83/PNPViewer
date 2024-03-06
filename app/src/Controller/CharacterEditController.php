<?php

namespace App\Controller;

use App\Entity\CharacterClassLevel;
use App\Entity\CharacterData;
use App\Entity\CharacterStatValue;
use App\Tools\Character\Factory\CharacterEditorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/character/edit', name: 'app_character_edit')]
class CharacterEditController extends AbstractController
{
    #[Route('/stat', name: '.stat')]
    public function editStatValue(
        Request $request,
        EntityManagerInterface $entityManager): Response
    {
        if(!$request->isXmlHttpRequest()) return new Response("", "400");

        $valueId = $request->get("valueId");
        $newValue = $request->get("newValue");

        $repoCharacterStatValue = $entityManager->getRepository(CharacterStatValue::class);

        /** @var CharacterStatValue $characterStatValue */
        $characterStatValue = $repoCharacterStatValue->find($valueId);

        /** @var CharacterData $character */
        $character = $characterStatValue->getCharacterData();

        $characterEditor = CharacterEditorFactory::get($entityManager, $character->getRuleSet());

        $characterEditor->setCharacter($character)
            ->setStatValue($characterStatValue->getCharacterStat(), $newValue)
            ->saveCharacter();

        return new Response("", "201");
    }

    #[Route('/class', name: '.class')]
    public function editClassValue(
        Request $request,
        EntityManagerInterface $entityManager): Response
    {
        if(!$request->isXmlHttpRequest()) return new Response("", "400");

        $classLevelId = $request->request->get("valueId");
        $newValue = $request->request->get("newValue");

        $repoCharacterClassLevel = $entityManager->getRepository(CharacterClassLevel::class);

        /** @var CharacterClassLevel $characterClassLevel */
        $characterClassLevel = $repoCharacterClassLevel->find($classLevelId);

        /** @var CharacterData $character */
        $character = $characterClassLevel->getCharacterData();

        $characterEditor = CharacterEditorFactory::get($entityManager, $character->getRuleSet());

        $characterEditor->setCharacter($character)
            ->setClassLevel($characterClassLevel->getCharacterClass(), $newValue)
            ->saveCharacter();

        return new Response("", "201");
    }
}
