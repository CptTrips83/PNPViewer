<?php

namespace App\Controller;

use App\Entity\CharacterStatCategory;
use App\Form\CharacterStatCategoryType;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterStatCategoryController extends AbstractController
{
    use ControllerForm;
    private EntityManagerInterface $_entityManager;

    #[Route('/character/stat/category/create', name: 'app_character_stat_category_create')]
    public function createCategoryForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterStatCategory::class,
            CharacterStatCategoryType::class
        );

        return $this->render('character_stat_category/form.html.twig', [
            'controller_name' => 'CharacterStatCategoryController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/character/stat/category/edit/{id}', name: 'app_character_stat_category_edit')]
    public function editCategoryForm(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterStatCategory::class,
            CharacterStatCategoryType::class,
            $id
        );

        return $this->render('character_stat_category/form.html.twig', [
            'controller_name' => 'CharacterStatCategoryController',
            'form' => $form->createView()
        ]);
    }

    public function createSuccessFlashMessage(): void
    {
        $this->addFlash('success', 'Kategorie wurde gespeichert');
    }
}
