<?php

namespace App\Controller;

use App\Entity\CharacterStat;
use App\Form\CharacterStatType;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterStatController extends AbstractController
{
    use ControllerForm;
    private EntityManagerInterface $_entityManager;

    #[Route('/character/stat/create', name: 'app_character_stat_create')]
    public function createCharacterStat(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterStat::class,
            CharacterStatType::class
        );

        return $this->render('character_stat/form.html.twig', [
            'controller_name' => 'CharacterStatCategoryController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/character/stat/edit/{id}', name: 'app_character_stat_edit')]
    public function editCharacterStat(Request $request, int $id, EntityManagerInterface $entityManager) : Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterStat::class,
            CharacterStatType::class,
            $id
        );

        return $this->render('character_stat/form.html.twig', [
            'controller_name' => 'CharacterStatCategoryController',
            'form' => $form->createView()
        ]);
    }

    private function createSuccessFlashMessage(): void
    {
        $this->addFlash('success', 'Charakter-Wert wurde gespeichert');
    }
}
