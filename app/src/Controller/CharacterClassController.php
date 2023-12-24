<?php

namespace App\Controller;

use App\Entity\CharacterClass;
use App\Entity\CharacterStat;
use App\Entity\RuleSet;
use App\Form\CharacterClassType;
use App\Form\CharacterStatType;
use App\Traits\ControllerEntityManager;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterClassController extends AbstractController
{
    use ControllerForm;
    use ControllerEntityManager;
    private EntityManagerInterface $_entityManager;

    #[Route('/character/create/class', name: 'app_character_class_create')]
    public function createCharacterClass(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->loadEntityManager($entityManager);

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterClass::class,
            CharacterClassType::class
        );

        $class = $form->getData();

        $redirectResponse = $this->redirectOnFormCompletion($form,
            'app_character_class_edit',
            ['id' => $class->getId()]);

        if($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('character_class/form.html.twig', [
                'controller_name' => 'CharacterClassController',
                'form' => $form
            ]);
        }
    }

    #[Route('/character/edit/class/{id}', name: 'app_character_class_edit')]
    public function editCharacterClass(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->loadEntityManager($entityManager);

        $form = $this->processForm($this->_entityManager,
            $request,
            CharacterClass::class,
            CharacterClassType::class,
            $id
        );

        return $this->render('character_class/form.html.twig', [
            'controller_name' => 'CharacterClassController',
            'form' => $form->createView()
        ]);
    }
    #[Route('/character/list/class/{ruleSetId}', name: 'app_character_class_list')]
    public function listCharacterClass(Request $request, int $ruleSetId, EntityManagerInterface $entityManager) : Response
    {
        $this->loadEntityManager($entityManager);

        $repoRuleSet = $this->_entityManager->getRepository(RuleSet::class);
        $repoClass = $this->_entityManager->getRepository(CharacterClass::class);

        $ruleSet = $repoRuleSet->find($ruleSetId);

        $classes = [];

        if($ruleSet) {

            $classes = $repoClass->findBy([
                'ruleSet' => $ruleSet
            ]);
        }

        return $this->render('character_class/list.html.twig', [
            'controller_name' => 'CharacterClassController',
            'classes' => $classes,
            'ruleSet' => $ruleSet
        ]);
    }

    private function createSuccessFlashMessage(): void
    {
        $this->addFlash('success', 'Charakter-Klasse wurde gespeichert');
    }
}
