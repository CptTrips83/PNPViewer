<?php

namespace App\Controller;

use App\Entity\CharacterStat;
use App\Entity\CharacterStatCategory;
use App\Entity\RuleSet;
use App\Form\CharacterStatType;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterStatController extends AbstractController
{
    use ControllerForm;
    private EntityManagerInterface $_entityManager;

    #[Route('/create/character/stat', name: 'app_character_stat_create')]
    public function createCharacterStat(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterStat::class,
            CharacterStatType::class
        );

        $redirectResponse = $this->redirectOnFormCompletion($form,
            'app_character_stat_list',
            ['ruleSetId' => '1']);

        if($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('character_stat/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/edit/character/stat/{id}', name: 'app_character_stat_edit')]
    public function editCharacterStat(Request $request, int $id, EntityManagerInterface $entityManager) : Response
    {
        $this->_entityManager = $entityManager;

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterStat::class,
            CharacterStatType::class,
            $id
        );

        $repoCategories = $this->_entityManager->getRepository(CharacterStatCategory::class);

        $categories = $repoCategories->findAll();

        $redirectResponse = $this->redirectOnFormCompletion($form,
            'app_character_stat_list',
            ['ruleSetId' => '1']);

        if($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('character_stat/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView(),
                'categories' => $categories
            ]);
        }
    }

    #[Route('/list/character/stat/{ruleSetId}', name: 'app_character_stat_list')]
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
