<?php

namespace App\Controller;

use App\Entity\CharacterStat;
use App\Entity\PNPGroup;
use App\Entity\PNPUser;
use App\Form\CharacterStatType;
use App\Form\PNPGroupType;
use App\Traits\ControllerEntityManager;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PNPGroupController extends AbstractController
{
    use ControllerForm;
    use ControllerEntityManager;

    #[Route('/pnp/group/list', name: 'app_pnp_group_list')]
    public function list(): Response
    {
        return $this->render('pnp_group/list.html.twig', [
            'controller_name' => 'PNPGroupController',
        ]);
    }

    #[Route('/pnp/group/create', name: 'app_pnp_group_create')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->setEntityManager($entityManager);

        $form = $this->processForm($this->_entityManager,
            $request,
            PNPGroup::class,
            PNPGroupType::class
        );
        $this->assignGameMasterToGroup($form);

        $redirectResponse = $this->redirectOnFormCompletion($form,
            'app_pnp_group_list');

        if($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('pnp_group/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/pnp/group/edit', name: 'app_pnp_group_edit')]
    public function edit(): Response
    {
        return $this->render('pnp_group/form.html.twig', [
            'controller_name' => 'PNPGroupController',
        ]);
    }

    /**
     * Weist den aktuell angemeldeten User der neuen Gruppe als GameMaster zu
     * @param FormInterface $form Form mit den Daten der neuen PNPGroup
     * @return void
     */
    public function assignGameMasterToGroup(FormInterface $form): void
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $dataObj = $form->getData();

            $repoUser = $this->_entityManager->getRepository(PNPUser::class);

            $user = $repoUser->findOneBy([
                'username' => $this->getUser()->getUserIdentifier()
            ]);

            $user->addGameMasterGroup($dataObj);
            $this->_entityManager->flush();
        }
    }

    private function createSuccessFlashMessage(): void
    {
        $this->addFlash('success', 'PNP-Gruppe wurde gespeichert');
    }
}
