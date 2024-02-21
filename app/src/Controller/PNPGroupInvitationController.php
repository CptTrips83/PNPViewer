<?php

namespace App\Controller;

use App\Entity\CharacterData;
use App\Entity\PNPGroup;
use App\Entity\PNPUser;
use App\Tools\PNPGroup\InvitationTools;
use App\Traits\ControllerEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('group/invitation', name: 'app_pnp_group_invitation')]
class PNPGroupInvitationController extends AbstractController
{
    use ControllerEntityManager;

    #[Route('/list/groups', name: '.list_groups')]
    public function listInvitation(
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->setEntityManager($entityManager);

        $groups = $this->_entityManager->getRepository(PNPGroup::class)
            ->findBy([
                'gameMaster' => $this->getUser()
            ]);

        return $this->render('pnp_group_invitation/list.html.twig',[
            'groups' => $groups
            ]);
    }

    #[Route('/generate/{pnpGroupId}/{pnpUserId}', name: '.generate')]
    public function createInvitation(
        EntityManagerInterface $entityManager,
        string $pnpGroupId,
        string $pnpUserId
    ): Response
    {
        $invitationCode = "";

        $this->setEntityManager($entityManager);

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

    #[Route('/show/{invitationCode}', name: '.show')]
    public function showInvitation(
        EntityManagerInterface $entityManager,
        string $invitationCode
    ): Response
    {
        $this->setEntityManager($entityManager);

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

    #[Route('/redeem/{invitationCode}', name: '.redeem')]
    public function redeemInvitation(
        EntityManagerInterface $entityManager,
        string $invitationCode
    ): Response
    {
        $user = $this->getUser();
        if(!$user) return $this->redirectToRoute('app_login');

        $this->setEntityManager($entityManager);

        $invite = null;

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

    #[Route('/join/{invitationCode}/{characterId}', name: '.join')]
    public function join(
        EntityManagerInterface $entityManager,
        string $invitationCode,
        string $characterId
    ): Response
    {
        $this->setEntityManager($entityManager);

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

    #[Route('/error/{invitationCode}', name: '.error')]
    public function error(
        EntityManagerInterface $entityManager,
        string $invitationCode
    ): Response
    {

        return $this->render('pnp_group_invitation/error.html.twig',[
                'invitationCode' => $invitationCode
            ]);
    }
}
