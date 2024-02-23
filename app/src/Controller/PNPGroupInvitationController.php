<?php

namespace App\Controller;

use App\Entity\CharacterData;
use App\Entity\PNPGroup;
use App\Entity\PNPUser;
use App\Tools\PNPGroup\InvitationTools;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

/**
 * Class PNPGroupInvitationController
 *
 * This class is responsible for handling group invitation related operations.
 */
#[Route('group/invitation', name: 'app_pnp_group_invitation')]
class PNPGroupInvitationController extends AbstractController
{

    public function __construct(
         private readonly EntityManagerInterface $_entityManager
    )
    {

    }

    /**
     * Creates an invitation for a specific user to join a group.
     *
     * @param string $pnpGroupId The ID of the group to generate the invitation for.
     * @param string $pnpUserId The ID of the user to generate the invitation for.
     *
     * @return Response The redirect response to the show invitation page if successful, or the error page otherwise.
     *
     * @Route('/generate/{pnpGroupId}/{pnpUserId}', name='.generate')
     */
    #[Route('/generate/{pnpGroupId}/{pnpUserId}', name: '.generate')]
    public function createInvitation(
        string $pnpGroupId,
        string $pnpUserId
    ): Response
    {
        $invitationCode = "";

        $invitationTool = new InvitationTools($this->_entityManager);

        $group = $this->_entityManager->getRepository(PNPGroup::class)->find($pnpGroupId);
        $user = $this->_entityManager->getRepository(PNPUser::class)->find($pnpUserId);
        try {
            $invitationCode = $invitationTool->createInvitation($group, $user);
            if(!$invitationCode) throw new Exception('Failed to create the invitation');
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_pnp_group_invitation.error', [
                'invitationCode' => $invitationCode
            ]);
        }

        return $this->redirectToRoute(
            'app_pnp_group_invitation.show',
            ['invitationCode' => $invitationCode]
        );
    }

    /**
     * Retrieves the data for a specific invitation and renders the invitation details.
     *
     * @param string $invitationCode The code of the invitation to be displayed.
     *
     * @return Response The rendered HTML template containing the invitation details.
     *
     * @Route('/show/{invitationCode}', name='.show')
     */
    #[Route('/show/{invitationCode}', name: '.show')]
    public function showInvitation(
        string $invitationCode
    ): Response
    {
        $invitationTool = new InvitationTools($this->_entityManager);
        try {
            $invite = $invitationTool->getInvitationData($invitationCode);
            if ($invite === null) {
                throw new Exception('Can`t find invitation for invitation Code');
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_pnp_group_invitation.error', [
                'invitationCode' => $invitationCode
            ]);
        }


        return $this->render('pnp_group_invitation/show.html.twig', [
            'groupName' => $invite->getInviteGroup()->getName(),
            'invitationCode' => $invitationCode
        ]);
    }

    /**
     * Redeems an invitation by retrieving and processing the invitation data.
     *
     * @param string $invitationCode The invitation code to redeem.
     * @return Response The rendered HTML template containing the redemption form and available characters.
     *
     * @Route('/redeem/{invitationCode}', name='.redeem')
     */
    #[Route('/redeem/{invitationCode}', name: '.redeem')]
    public function redeemInvitation(
        string $invitationCode
    ): Response
    {
        $user = $this->getUser();
        if(!$user) return $this->redirectToRoute('app_login');

        $invitationTool = new InvitationTools($this->_entityManager);
        try {
            $invite = $invitationTool->getInvitationData($invitationCode);
            if(!$invite) throw new Exception('Invitation not found');
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_pnp_group_invitation.error', [
                'invitationCode' => $invitationCode
            ]);
        }

        $data = $this->_entityManager->getRepository(CharacterData::class)
            ->findBy([
                'user' => $user,
                'ruleSet' => $invite->getInviteGroup()->getRuleSet()
            ]);

        $characters = array_filter($data, function($character) {
            return $character->getPnpGroup() === null;
        });

        return $this->render('pnp_group_invitation/redeem.html.twig', [
            'groupName' => $invite->getInviteGroup()->getName(),
            'invitationCode' => $invitationCode,
            'characters' => $characters
        ]);
    }

    /**
     * Redeems an invitation code and associates the character with the group.
     *
     * @param string $invitationCode The invitation code to redeem.
     * @param string $characterId The ID of the character.
     * @return Response A redirect response to the character list page.
     *
     * @Route('/join/{invitationCode}/{characterId}', name='.join')
     */
    #[Route('/join/{invitationCode}/{characterId}', name: '.join')]
    public function join(
        string $invitationCode,
        string $characterId
    ): Response
    {

        $character = $this->_entityManager->getRepository(CharacterData::class)->find($characterId);

        $invitationTool = new InvitationTools($this->_entityManager);
        try {
            $invitationTool->redeemInvitationCode($invitationCode, $character);
        } catch (Exception|Throwable $e) {
            error_log($e->getMessage());
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_pnp_group_invitation.error', [
                'invitationCode' => $invitationCode
            ]);
        }

        return $this->redirectToRoute('app_character_list');
    }

    /**
     * Handles and displays an error page for a specific invitation code.
     *
     * @param string $invitationCode The code associated with the error.
     *
     * @return Response The rendered HTML template for the error page.
     *
     * @Route('/error/{invitationCode}', name='.error')
     */
    #[Route('/error/{invitationCode}', name: '.error')]
    public function error(
        string $invitationCode
    ): Response
    {

        return $this->render('pnp_group_invitation/error.html.twig',[
                'invitationCode' => $invitationCode
            ]);
    }
}
