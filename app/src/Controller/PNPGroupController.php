<?php

namespace App\Controller;

use App\Entity\CharacterData;
use App\Entity\PNPGroup;
use App\Entity\PNPUser;
use App\Form\PNPGroupType;
use App\Traits\ControllerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/group', name: 'app_pnp_group')]
class PNPGroupController extends AbstractController
{
    use ControllerForm;

    public function __construct(
        private readonly EntityManagerInterface $_entityManager
    ) {
    }

    /**
     * Returns a response to display a list of PNPGroups for the current user who is a game master.
     *
     * @return Response A response containing the rendered 'pnp_group/list.html.twig'
     * template and the 'groups' variable, which holds the list of PNPGroups
     */
    #[Route('/list', name: '.list')]
    public function list(): Response
    {
        $groups = $this->_entityManager->getRepository(PNPGroup::class)
            ->findBy([
                'gameMaster' => $this->getUser()
            ]);

        return $this->render('pnp_group/list.html.twig', [
            'groups' => $groups,
        ]);
    }

    /**
     * Creates a new PNPGroup entity.
     * @param Request $request The request object.
     * @return Response The response object.
     * @Route('/create', name: '.create')
     */
    #[Route('/create', name: '.create')]
    public function create(
        Request $request
    ): Response {

        $form = $this->processForm(
            $this->_entityManager,
            $request,
            PNPGroup::class,
            PNPGroupType::class
        );

        $this->assignGameMasterToGroup($form);

        $redirectResponse = $this->redirectOnFormCompletion(
            $form,
            'app_pnp_group.list'
        );

        if ($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('pnp_group/form.html.twig', [
                'controller_name' => 'CharacterStatCategoryController',
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * Edits the PNPGroup with the given ID
     *
     * @param Request $request The request object
     * @param int $id The ID of the PNPGroup to edit
     *
     * @return Response The response object
     */
    #[Route('/edit/{id}', name: '.edit')]
    public function edit(
        Request $request,
        int $id
    ): Response {
        $form = $this->processForm(
            $this->_entityManager,
            $request,
            PNPGroup::class,
            PNPGroupType::class,
            $id
        );

        $redirectResponse = $this->redirectOnFormCompletion(
            $form,
            'app_pnp_group.list'
        );
        if ($redirectResponse != null) {
            return $redirectResponse;
        } else {
            return $this->render('pnp_group/form.html.twig', [
                'controller_name' => 'PNPGroupController',
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * Löscht eine Gruppe aus der Datenbank und entfernt alle zugeordneten Charaktere und Einladungen
     * @param EntityManagerInterface $entityManager Das Entity Manager-Objekt für den Datenbankzugriff
     * @param int $id Die ID der zu löschenden Gruppe
     * @return Response Die HTTP Response nach dem Löschen der Gruppe
     */
    #[Route('/delete/{id}', name: '.delete')]
    public function delete(
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        $group = $entityManager->getRepository(PNPGroup::class)
            ->find($id);

        foreach ($group->getCharacters() as $character) {
            $group->removeCharacter($character);
        }

        foreach ($group->getInvites() as $invite) {
            $group->removeInvite($invite);
            $entityManager->remove($invite);
        }

        $entityManager->remove($group);
        $entityManager->flush();

        $this->createSuccessFlashMessage("Gruppe wurde entfernt.");


        return $this->redirectToRoute('app_pnp_group.list');
    }

    #[Route('/leave/{characterId}', name: '.leave')]
    public function leave(
        EntityManagerInterface $entityManager,
        Request $request,
        int $characterId
    ) : Response {
        $character = $entityManager->getRepository(CharacterData::class)
            ->find($characterId);

        $group = $character->getPnpGroup();

        if ($group !== null) {
            $group->removeCharacter($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_character_list');
    }

    /**
     * Assigns a game master to a group based on the submitted form data.
     *
     * @param FormInterface $form The form containing the submitted data.
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

    /**
     * Creates a success flash message.
     *
     * @param string $message The success message to display. Defaults to 'Gruppe wurde gespeichert'.
     * @return void
     */
    private function createSuccessFlashMessage(string $message = 'Gruppe wurde gespeichert'): void
    {
        $this->addFlash('success', $message);
    }
}
