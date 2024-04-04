<?php

namespace App\Tests\Tools\PNPGroup;

use App\Entity\CharacterData;
use App\Entity\PNPGroup;
use App\Entity\PNPGroupInvite;
use App\Entity\PNPUser;
use App\Entity\RuleSet;
use App\Tools\PNPGroup\InvitationTools;
use App\Tools\Tests\AbstractKernelTest;
use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Exception;
use Throwable;

/**
 * Class InvitationToolsTest
 *
 * This class contains tests for methods in the InvitationTools class.
 *
 * @package App\Tests\Tools\PNPGroup
 */
class InvitationToolsTest extends AbstractKernelTest
{
    private RuleSet $ruleset;
    private PNPUser $user;
    private PNPGroup $group;

    protected function setUp(): void
    {
        $this->initialize();
    }

    /**
     * Test for uniqidReal method in InvitationTools
     *
     * This method generates a unique ID using either the random_bytes or openssl_random_pseudo_bytes functions,
     * or throws an Exception if they're not available. The length of the ID can be customized.
     * The test will check that the length of the generated ID matches the provided length,
     * and that an Exception is thrown
     * if no cryptographically secure function is available.
     */
    public function testUniqueIdReal()
    {
        try {
            // we expect a string of length 13 by default
            $id = InvitationTools::uniqueIdReal();
            $this->assertEquals(13, strlen($id));
            
            // test with a different length
            $length = 20;
            $id = InvitationTools::uniqueIdReal($length);
            $this->assertEquals($length, strlen($id));
            
            // test that an Exception is thrown if no function is available
            if (!function_exists("random_bytes") && !function_exists("openssl_random_pseudo_bytes")) {
                $this->expectException(Exception::class);
                $id = InvitationTools::uniqueIdReal();
            }
        } catch (Exception $exception) {
            $this->fail('Exception thrown: ' . $exception->getMessage());
        }
    }

    /**
     * Test the creation of group invitations with and without invited users.
     *
     * @return void
     */
    public function testInvitationCreation() : void
    {
        try {
            $repoGroupInvitation = $this->entityManager->getRepository(PNPGroupInvite::class);

            $this->createMocks();

            $invitationTool = new InvitationTools($this->entityManager);

            // test Invite Creation only with PNPGroup
            $code = $invitationTool->createInvitation($this->group);
            $this->assertNotEmpty($code);
            $this->assertIsString($code);
            $invite = $repoGroupInvitation->findOneBy([
                'inviteCode' => $code
            ]);
            $this->assertInstanceOf(PNPGroupInvite::class, $invite);
            $this->assertEquals($this->group, $invite->getInviteGroup());
            $this->assertNull($invite->getInvitedUser());
            $this->assertInstanceOf(DateTimeInterface::class, $invite->getInviteDate());

            // test Invite Creation with PNPGroup & PNPUser
            $code = $invitationTool->createInvitation($this->group, $this->user);
            $this->assertNotEmpty($code);
            $this->assertIsString($code);
            $invite = $repoGroupInvitation->findOneBy([
                'inviteCode' => $code
            ]);
            $this->assertInstanceOf(PNPGroupInvite::class, $invite);
            $this->assertEquals($this->group, $invite->getInviteGroup());
            $this->assertEquals($this->user, $invite->getInvitedUser());
            $this->assertInstanceOf(DateTimeInterface::class, $invite->getInviteDate());
        } catch (Exception $exception) {
            $this->fail('Exception thrown: ' . $exception->getMessage());
        }
    }

    /**
     * Test the retrieval of invitation data based on a given invitation code.
     *
     * @return void
     * @throws Throwable
     */
    public function testGetInvitationData() : void
    {
        try {
            $this->createMocks();

            $invitationTool = new InvitationTools($this->entityManager);

            $code = $invitationTool->createInvitation($this->group);
            $invite = $invitationTool->getInvitationData($code);

            $this->assertInstanceOf(PNPGroupInvite::class, $invite);
            $this->assertEquals("test group", $invite->getInviteGroup()->getName());
        } catch (Exception $exception) {
            $this->fail('Exception thrown: ' . $exception->getMessage());
        }
    }

    /**
     * Test the redemption of a group invitation for a character.
     *
     * @return void
     * @throws Throwable
     */
    public function testInvitationRedeemWithoutUser() : void
    {
        try {
            $this->createMocks();

            $character = new CharacterData();
            $character->setUser($this->user);
            $character->setName("Test");
            $character->setRuleSet($this->ruleset);

            $this->entityManager->persist($character);
            $this->entityManager->flush();

            $invitationTool = new InvitationTools($this->entityManager);
            $code = $invitationTool->createInvitation($this->group);
            $invitationTool->redeemInvitationCode($code, $character);

            $this->assertEquals($this->group, $character->getPnpGroup());
        } catch (Exception $exception) {
            $this->fail('Exception thrown: ' . $exception->getMessage());
        }
    }

    /**
     * Test the redemption of a group invitation for a character with a user.
     *
     * @return void
     * @throws Throwable
     */
    public function testInvitationRedeemWithUser() : void
    {
        try {
            $this->createMocks();

            $character = new CharacterData();
            $character->setUser($this->user);
            $character->setName("Test");
            $character->setRuleSet($this->ruleset);

            $this->entityManager->persist($character);
            $this->entityManager->flush();

            $invitationTool = new InvitationTools($this->entityManager);
            $code = $invitationTool->createInvitation($this->group, $this->user);
            $invitationTool->redeemInvitationCode($code, $character);

            $this->assertEquals($this->group, $character->getPnpGroup());
        } catch (Exception $exception) {
            $this->fail('Exception thrown: ' . $exception->getMessage());
        }
    }

    /**
     * Test the redemption of a group invitation with a wrong user.
     *
     * @return void
     * @throws Throwable
     */
    public function testInvitationRedeemWithWrongUser() : void
    {
        try {
            $this->createMocks();

            $wrongUser = new PNPUser();
            $wrongUser->setUsername("test2");
            $wrongUser->setPassword("test2");
            $this->entityManager->persist($wrongUser);
            $this->entityManager->flush();

            $character = new CharacterData();
            $character->setUser($wrongUser);
            $character->setName("Test");
            $character->setRuleSet($this->ruleset);

            $this->entityManager->persist($character);
            $this->entityManager->flush();

            $invitationTool = new InvitationTools($this->entityManager);
            $code = $invitationTool->createInvitation($this->group, $this->user);
            $invitationTool->redeemInvitationCode($code, $character);

            $this->assertNotEquals($this->group, $character->getPnpGroup());
        } catch (Exception $exception) {
            $this->fail('Exception thrown: ' . $exception->getMessage());
        }
    }

    /**
     * Test the redemption of a group invitation for a character with an expired lifetime.
     *
     * @return void
     * @throws Throwable
     */
    public function testInvitationRedeemLifeTimeExpired() : void
    {
        try {
            $this->createMocks();

            $repoInvites = $this->entityManager->getRepository(PNPGroupInvite::class);

            $wrongUser = new PNPUser();
            $wrongUser->setUsername("test2");
            $wrongUser->setPassword("test2");
            $this->entityManager->persist($wrongUser);
            $this->entityManager->flush();

            $character = new CharacterData();
            $character->setUser($wrongUser);
            $character->setName("Test");
            $character->setRuleSet($this->ruleset);

            $this->entityManager->persist($character);
            $this->entityManager->flush();

            $invitationTool = new InvitationTools($this->entityManager);
            $code = $invitationTool->createInvitation($this->group, $this->user);

            $invite = $repoInvites->findOneBy([
                'inviteCode' => $code
            ]);

            $invite->setInviteDate(new DateTime('1983-12-19 00:00:00'));

            $invitationTool->redeemInvitationCode($code, $character);

            $this->assertNotEquals($this->group, $character->getPnpGroup());
        } catch (Exception $exception) {
            $this->fail('Exception thrown: ' . $exception->getMessage());
        }
    }

    /**
     * Create mock PNPUser, PNPGroup, and RuleSet objects for testing purposes.
     *
     * @return void
     * @throws Throwable
     */
    private function createMocks() : void
    {
        // create mock PNPUser and PNPGroup
        $this->ruleset = new RuleSet();
        $this->ruleset->setName("Test");
        $this->entityManager->persist($this->ruleset);
        $this->entityManager->flush();

        $this->user = new PNPUser();
        $this->user->setUsername("test");
        $this->user->setPassword("test");
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();

        $this->group = new PNPGroup();
        $this->group->setName("test group");
        $this->group->setDescription("test description");
        $this->group->setRuleSet($this->ruleset);
        $this->group->setGameMaster($this->user);
        $this->entityManager->persist($this->group);
        $this->entityManager->flush();
    }
}
