<?php

namespace App\Controller;

use App\Entity\CharacterClass;
use App\Entity\CharacterStatCategory;
use App\Entity\RuleSet;
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

    #[Route('/create/character/stat/category', name: 'app_character_stat_category_create')]
    public function createCategoryForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterStatCategory::class,
            CharacterStatCategoryType::class
        );

        $category = $form->getData();

        $redirectResponse = $this->redirectOnFormCompletion($form,
            'app_character_stat_category_edit',
            ['id' => $category->getId()]);

        if($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('character_stat_category/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/edit/character/stat/category/{id}', name: 'app_character_stat_category_edit')]
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

    #[Route('/list/character/stat/category/{ruleSetId}', name: 'app_character_stat_category_list')]
    public function listCharacterClass(Request $request, int $ruleSetId, EntityManagerInterface $entityManager) : Response
    {
        $this->_entityManager = $entityManager;

        $repoRuleSet = $this->_entityManager->getRepository(RuleSet::class);
        $repoCategory = $this->_entityManager->getRepository(CharacterStatCategory::class);

        $ruleSet = $repoRuleSet->find($ruleSetId);

        $categories = [];

        if($ruleSet) {

            $categories = $repoCategory->findBy([
                'ruleSet' => $ruleSet
            ]);
        }

        return $this->render('character_stat_category/list.html.twig', [
            'controller_name' => 'CharacterClassController',
            'categories' => $categories,
            'ruleSet' => $ruleSet
        ]);
    }

    public function createSuccessFlashMessage(): void
    {
        $this->addFlash('success', 'Kategorie wurde gespeichert');
    }
}
