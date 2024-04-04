<?php

namespace App\Controller;

use App\Entity\CharacterStat;
use App\Entity\CharacterStatCategory;
use App\Entity\RuleSet;
use App\Form\CharacterStatType;
use App\Traits\ControllerEntityManager;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterStatController extends AbstractController
{
    use ControllerEntityManager;
    use ControllerForm;
    private EntityManagerInterface $entityManager;

    #[Route('/ruleset/create/stat', name: 'app_ruleset_stat_create')]
    public function createCharacterStat(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->setEntityManager($entityManager);

        $form = $this->processForm(
            $this->entityManager,
            $request,
            CharacterStat::class,
            CharacterStatType::class
        );

        $redirectResponse = $this->redirectOnFormCompletion(
            $form,
            'app_ruleset_stat_list',
            ['ruleSetId' => '1']
        );

        if ($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('character_stat/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/ruleset/edit/stat/{id}', name: 'app_ruleset_stat_edit')]
    public function editCharacterStat(Request $request, int $id, EntityManagerInterface $entityManager) : Response
    {
        $this->setEntityManager($entityManager);

        $form = $this->processForm(
            $this->entityManager,
            $request,
            CharacterStat::class,
            CharacterStatType::class,
            $id
        );

        $repoCategories = $this->entityManager->getRepository(CharacterStatCategory::class);

        $categories = $repoCategories->findAll();

        $redirectResponse = $this->redirectOnFormCompletion(
            $form,
            'app_ruleset_stat_list',
            ['ruleSetId' => '1']
        );

        if ($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('character_stat/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView(),
                'categories' => $categories
            ]);
        }
    }

    #[Route('/ruleset/list/stat/{ruleSetId}', name: 'app_ruleset_stat_list')]
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

        return $this->render('character_stat/list.html.twig', [
            'controller_name' => 'CharacterClassController',
            'categories' => $categories,
            'ruleSet' => $ruleSet
        ]);
    }

    private function createSuccessFlashMessage(): void
    {
        $this->addFlash('success', 'Charakter-Wert wurde gespeichert');
    }
}
