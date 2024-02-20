<?php

namespace App\Tools\PNPGroup;

use App\Entity\CharacterData;
use App\Entity\PNPGroup;
use App\Entity\PNPGroupInvite;
use App\Entity\PNPUser;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Throwable;

/**
 * Class InvitationTools
 *
 * This class provides methods for generating and redeeming invitations.
 */
readonly class InvitationTools
{
    public function __construct(
        private EntityManagerInterface $_entityManager
    )
    {

    }

    /**
     * Get invitation data for a given invite code
     *
     * @param string $code The invite code to get the invitation data for
     *
     * @return PNPGroupInvite|null Returns the invitation data if found, null otherwise
     * @throws Exception Any exception that occurred while retrieving the invitation data
     */
    public function getInvitationData(string $code) : PNPGroupInvite | null
    {
        try {
            $repoInvitations = $this->_entityManager->getRepository(PNPGroupInvite::class);

            return $repoInvitations->findOneBy([
                'inviteCode' => $code
            ]);
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            throw $exception;
        }
    }

    /**
     * Generates an invitation code and creates a group invitation.
     *
     * @param PNPGroup $group The group to create the invitation for.
     * @param ?PNPUser $user Optional. The user to associate with the invitation. Defaults to null.
     * @return string The generated invitation code, or false if an error occurred.
     *
     * @throws Exception When an error occurs while generating the invitation code or creating the invitation.
     */
    public function createInvitation(
        PNPGroup $group,
        ?PNPUser $user = null
    ) : string
    {
        try {
            $this->_entityManager->getConnection()->beginTransaction();

            $invitationCode = self::uniqueIdReal();

            $groupInvitation = new PNPGroupInvite();

            $groupInvitation->setInviteDate(new DateTime());
            $groupInvitation->setInviteCode($invitationCode);
            $groupInvitation->setInviteLifeTime(new DateInterval('P2D'));

            $this->_entityManager->persist($groupInvitation);

            $group->addInvite($groupInvitation);
            $user?->addGroupInvite($groupInvitation);

            $this->_entityManager->flush();
            $this->_entityManager->getConnection()->commit();

        } catch(Exception $exception) {
            error_log($exception->getMessage());
            $this->_entityManager->getConnection()->rollBack();
            throw $exception;
        }

        return $invitationCode;
    }

    /**
     * Redeem an invitation code for a character
     *
     * @param string $code The invitation code to redeem
     * @param CharacterData $character The character to redeem the code for
     *
     * @throws Exception If an error occurs during the process
     * @throws Throwable
     */
    public function redeemInvitationCode(
        string $code,
        CharacterData $character
    ) : void
    {
        try {
            $this->_entityManager->getConnection()->beginTransaction();
            $user = $character->getUser();
            $repoInvitations = $this->_entityManager->getRepository(PNPGroupInvite::class);

            $invite = $repoInvitations->findOneBy([
                'inviteCode' => $code
            ]);

            if ($this->checkInvite($invite, $user)) {
                $this->addCharacterToGroup($character, $invite->getInviteGroup());
                $this->removeInvitation($invite);
            }
            $this->_entityManager->getConnection()->commit();
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            $this->_entityManager->getConnection()->rollBack();
            throw $exception;
        }
    }

    /**
     * Check the validity of an invite for a given user
     *
     * @param PNPGroupInvite|null $invite The invite to be checked
     * @param PNPUser $user The user to check the invite against
     *
     * @return bool Returns true if the invite is valid for the user, false otherwise.
     * @throws Throwable
     */
    private function checkInvite(
        PNPGroupInvite | null $invite,
        PNPUser $user
    ) : bool
    {
        if ($invite) {
            if (!$this->checkInviteLifeTime($invite)) return false;
            if ($invite->getInvitedUser() === null ||
                $invite->getInvitedUser() === $user)
                return true;
        }
        return false;
    }

    /**
     * Check the validity of an invite based on its lifetime
     *
     * @param PNPGroupInvite $invite The invite to be checked
     *
     * @return bool Returns true if the invite is still within its lifetime, false otherwise.
     *
     * @throws Exception|Throwable If an error occurs while processing the invite lifetime
     */
    private function checkInviteLifeTime(PNPGroupInvite $invite) : bool
    {
        try {
            $date = new DateTime($invite->getInviteDate()->format("Y-m-d"));
            $date = $date->add($invite->getInviteLifeTime());
            if ($date < new DateTime()) {
                return false;
            }
            return true;
        } catch (Exception | Throwable $exception) {
            error_log($exception->getMessage());
            throw $exception;
        }
    }

    /**
     * Add a character to a group
     *
     * @param CharacterData $character The character to be added to the group
     * @param PNPGroup $group The group to which the character should be added
     *
     * @return void This method does not return a value
     */
    private function addCharacterToGroup(
        CharacterData $character,
        PNPGroup $group
    ) : void
    {
        $group->addCharacter($character);
        $this->_entityManager->flush();
    }

    /**
     * Remove an invitation from the database
     *
     * @param PNPGroupInvite $invite The invite to be removed
     *
     * @return void
     */
    private function removeInvitation(PNPGroupInvite $invite) : void
    {
        $this->_entityManager->remove($invite);
        $this->_entityManager->flush();
    }

    /**
     * Generates a cryptographically secure random unique ID.
     *
     * @param int $length The length of the generated unique ID. Defaults to 13.
     * @return string The generated unique ID.
     *
     * @throws Exception When no cryptographically secure random function is available.
     */
    public static function uniqueIdReal(int $length = 13) : string
    {

        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $length);
    }
}