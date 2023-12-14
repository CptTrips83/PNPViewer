<?php

namespace App\Controller;

use App\Entity\CharacterClass;
use App\Entity\CharacterStat;
use App\Form\CharacterClassType;
use App\Form\CharacterStatType;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterClassController extends AbstractController
{
    use ControllerForm;
    private EntityManagerInterface $_entityManager;

    #[Route('/character/class/create', name: 'app_character_class_create')]
    public function createCharacterClass(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterClass::class,
            CharacterClassType::class
        );

        return $this->render('character_class/form.html.twig', [
            'controller_name' => 'CharacterClassController',
            'form' => $form
        ]);
    }

    #[Route('/character/class/edit/{id}', name: 'app_character_class_edit')]
    public function editCharacterClass(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterClass::class,
            CharacterClassType::class,
            $id
        );

        return $this->render('character_class/form.html.twig', [
            'controller_name' => 'CharacterClassController',
            'form' => $form
        ]);
    }

    private function createSuccessFlashMessage(): void
    {
        $this->addFlash('success', 'Charakter-Klasse wurde gespeichert');
    }
}
