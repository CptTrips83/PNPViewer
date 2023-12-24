<?php

namespace App\Controller;

use App\Entity\CharacterClass;
use App\Entity\CharacterData;
use App\Entity\CharacterStat;
use App\Entity\CharacterStatCategory;
use App\Entity\RuleSet;
use App\Repository\CharacterStatRepository;
use App\Tools\Character\Factory\CharacterBuilderFactory;
use App\Tools\Character\Interfaces\CharacterBuilderInterface;
use App\Traits\ControllerEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterCreationController extends AbstractController
{
    use ControllerEntityManager;

    private EntityManagerInterface $_entityManager;
    #[Route('/character/creation/basics/{ruleSetId}', name: 'app_character_creation_basics')]
    public function basics(Request $request,
                           int $ruleSetId,
                           EntityManagerInterface $entityManager
    ) : Response
    {
        $this->loadEntityManager($entityManager);

        $repoRuleSet = $this->_entityManager->getRepository(RuleSet::class);
        $repoCharacterClass = $this->_entityManager->getRepository(CharacterClass::class);

        $ruleSet = $repoRuleSet->find($ruleSetId);

        $queryBuilder = $repoCharacterClass->createQueryBuilder('c')
            ->where('c.ruleSet = :ruleSet')
            ->setParameter(':ruleSet', $ruleSet);

        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true
            ])
            ->add('handle', TextType::class, [
                'label' => 'Handle',
                'required' => true
            ])
            ->add('characterClass', EntityType::class, [
                'label' => 'Klasse',
                'class' => CharacterClass::class,
                'query_builder' => $queryBuilder,
                'placeholder' => 'Klasse auswählen',
                'empty_data' => null,
                'required' => true
            ])
            ->add('speichern', SubmitType::class, [
            'label' => 'Fortfahren'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $character = CharacterBuilderFactory::get($this->_entityManager, $ruleSet)
                ->set("name", $data["name"])
                ->set("user", $this->getUser())
                ->set("nickname", $data["handle"])
                ->addClass($data["characterClass"], 1)
                ->buildCharacter();

            //dump($character);
            return $this->redirectToRoute('app_character_creation_details', [
                'characterId' => $character->getId()
            ]);

        }
        $route = 'character_creation/basics.html.twig';

        return $this->render($route, [
            'controller_name' => 'CharacterCreationController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/character/creation/details/{characterId}', name: 'app_character_creation_details')]
    public function details(Request $request,
                          int $characterId,
                          EntityManagerInterface $entityManager
    ): Response
    {
        $this->loadEntityManager($entityManager);

        $repoRuleSet = $this->_entityManager->getRepository(RuleSet::class);
        $repoCharacter = $this->_entityManager->getRepository(CharacterData::class);
        $repoStatCategory = $this->_entityManager->getRepository(CharacterStatCategory::class);

        $character = $repoCharacter->find($characterId);
        $ruleSet = $character->getRuleSet();
        $characterClass = $character->getCharacterClassLevels()[0]->getCharacterClass();

        $categories = $repoStatCategory->findBy([
            'ruleSet' => $ruleSet,
            'statsRequired' => 1,
            'classNeeded' => null
        ]);

        $categoriesClass = $repoStatCategory->findBy([
            'ruleSet' => $ruleSet,
            'statsRequired' => 1,
            'classNeeded' => $characterClass
        ]);

        $categories = array_merge($categories, $categoriesClass);

        $form = $this->createFormBuilder();

        foreach ($categories as $category) {
            $this->GenerateFormPartFromStatCategory(
              $form,
              $category
            );
        }

        $form = $form->add('speichern', SubmitType::class, [
                'label' => 'Fortfahren'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $characterBuilder = CharacterBuilderFactory::get($this->_entityManager, $ruleSet)
                ->setCharacter($character);

            foreach ($data as $key => $characterStat)
            {
                $characterBuilder = $characterBuilder
                    ->addStat($characterStat, 1);
            }

            $character = $characterBuilder->buildCharacter();

            return $this->redirectToRoute('app_character_creation_skills', [
                'characterId' => $character->getId()
            ]);
        }

        $route = 'character_creation/details.html.twig';

        return $this->render($route, [
            'controller_name' => 'CharacterCreationController',
            'form' => $form->createView()
        ]);
    }
    #[Route('/character/creation/skills/{characterId}', name: 'app_character_creation_skills')]
    public function skills(Request $request,
                           int $characterId,
                           EntityManagerInterface $entityManager
    ) : Response
    {
        $this->loadEntityManager($entityManager);

        $repoCharacter = $this->_entityManager->getRepository(CharacterData::class);
        $repoStatCategory = $this->_entityManager->getRepository(CharacterStatCategory::class);
        $repoStats = $this->_entityManager->getRepository(CharacterStat::class);

        $character = $repoCharacter->find($characterId);
        $ruleSet = $character->getRuleSet();
        $category = $repoStatCategory->findOneBy([
            'name' => 'skills'
        ]);

        $stats = $repoStats->findBy([
            'category' => $category
        ]);

        $form = $this->createFormBuilder();

        foreach ($stats as $stat)
        {
            $form = $this->GenerateFormPartFromStat(
                $form,
                $stat
            );
        }

        $form = $form->add('speichern', SubmitType::class, [
            'label' => 'Fortfahren'
        ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $characterBuilder = CharacterBuilderFactory::get($this->_entityManager, $ruleSet)
                ->setCharacter($character);

            $character = $this->buildCharacterStats($form,
                $characterBuilder,
                $repoStats
            );

            return $this->redirectToRoute('app_character_creation_perks', [
                'characterId' => $character->getId()
            ]);
        }

        $route = 'character_creation/skills.html.twig';

        return $this->render($route, [
            'controller_name' => 'CharacterCreationController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/character/creation/perks/{characterId}', name: 'app_character_creation_perks')]
    public function perks(Request $request,
                           int $characterId,
                           EntityManagerInterface $entityManager
    ) : Response
    {
        $this->loadEntityManager($entityManager);

        $repoCharacter = $this->_entityManager->getRepository(CharacterData::class);
        $repoStatCategory = $this->_entityManager->getRepository(CharacterStatCategory::class);
        $repoStats = $this->_entityManager->getRepository(CharacterStat::class);

        $character = $repoCharacter->find($characterId);
        $ruleSet = $character->getRuleSet();
        $categories = $repoStatCategory->findBy([
            'statsRequired' => '-1'
        ]);

        $form = $this->createFormBuilder();

        foreach ($categories as $category) {
            if(str_contains($category->getName(), 'perks')){
                $stats = $repoStats->findBy([
                    'category' => $category
                ]);

                foreach ($stats as $stat)
                {
                    $form = $this->GenerateFormPartFromStat(
                        $form,
                        $stat
                    );
                }
            }
        }
        $form = $form->add('speichern', SubmitType::class, [
            'label' => 'Fortfahren'
        ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $characterBuilder = CharacterBuilderFactory::get($this->_entityManager, $ruleSet)
                ->setCharacter($character)
                ->finishCreation();

            $character = $this->buildCharacterStats($form,
                $characterBuilder,
                $repoStats
            );

            return $this->redirectToRoute('app_character_show_details', [
                'characterId' => $character->getId()
            ]);
        }

        $route = 'character_creation/perks.html.twig';

        return $this->render($route, [
            'controller_name' => 'CharacterCreationController',
            'form' => $form->createView()
        ]);
    }

    private function GenerateFormPartFromStat(
        FormBuilderInterface $form,
        CharacterStat $stat
    ) : FormBuilderInterface
    {

        return $form->add($stat->getName(), IntegerType::class, [
            'label' => $stat->getDescription(),
            'data' => $stat->getMinValue(),
            'attr' => [
                'min' => $stat->getMinValue(),
                'max' => $stat->getHighestValue()
            ]
        ]);

    }

    private function GenerateFormPartFromStatCategory(
        FormBuilderInterface $form,
        CharacterStatCategory $category
    ) : FormBuilderInterface
    {
        $repo = $this->_entityManager->getRepository(CharacterStat::class);

        $queryBuilder = $repo->createQueryBuilder('c')
            ->where('c.category = :category')
            ->setParameter(':category', $category);

        return $form->add($category->getName(), EntityType::class, [
            'label' => ucfirst($category->getDescription()),
            'class' => CharacterStat::class,
            'query_builder' => $queryBuilder,
            'required' => true,
            'placeholder' => 'Bitte auswählen',
            'empty_data' => null,
        ]);
    }


    /**
     * @param FormInterface $form
     * @param CharacterBuilderInterface $characterBuilder
     * @param CharacterStatRepository $repoStats
     * @return CharacterData
     */
    public function buildCharacterStats(
        FormInterface $form,
        CharacterBuilderInterface $characterBuilder,
        CharacterStatRepository $repoStats): CharacterData
    {
        $data = $form->getData();

        foreach ($data as $name => $value) {
            $characterStat = $repoStats->findOneBy([
                'name' => $name
            ]);

            $characterBuilder = $characterBuilder
                ->addStat($characterStat, $value);
        }

        return $characterBuilder->buildCharacter();
    }
}
