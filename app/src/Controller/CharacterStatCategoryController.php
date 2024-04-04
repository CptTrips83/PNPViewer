<?php

namespace App\Controller;

use App\Entity\CharacterStatCategory;
use App\Entity\RuleSet;
use App\Form\CharacterStatCategoryType;
use App\Traits\ControllerEntityManager;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterStatCategoryController extends AbstractController
{
    use ControllerEntityManager;
    use ControllerForm;
    private EntityManagerInterface $entityManager;

    #[Route('/ruleset/create/stat/category', name: 'app_ruleset_stat_category_create')]
    public function createCategoryForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->setEntityManager($entityManager);

        $form = $this->processForm(
            $this->entityManager,
            $request,
            CharacterStatCategory::class,
            CharacterStatCategoryType::class
        );

        $form->getData();

        $redirectResponse = $this->redirectOnFormCompletion(
            $form,
            'app_ruleset_stat_category_list',
            ['ruleSetId' => '1']
        );

        if ($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('character_stat_category/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/ruleset/edit/stat/category/{id}', name: 'app_ruleset_stat_category_edit')]
    public function editCategoryForm(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->setEntityManager($entityManager);

        $form = $this->processForm(
            $this->entityManager,
            $request,
            CharacterStatCategory::class,
            CharacterStatCategoryType::class,
            $id
        );

        $redirectResponse = $this->redirectOnFormCompletion(
            $form,
            'app_ruleset_stat_category_list',
            ['ruleSetId' => '1']
        );
        if ($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('character_stat_category/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/ruleset/list/stat/category/{ruleSetId}', name: 'app_ruleset_stat_category_list')]
    public function listCharacterClass(int $ruleSetId, EntityManagerInterface $entityManager) : Response
    {
        $this->setEntityManager($entityManager);

        $repoRuleSet = $this->entityManager->getRepository(RuleSet::class);
        $repoCategory = $this->entityManager->getRepository(CharacterStatCategory::class);

        $ruleSet = $repoRuleSet->find($ruleSetId);

        $categories = [];

        if ($ruleSet) {
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
