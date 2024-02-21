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
    private RuleSet $_ruleset;
    private PNPUser $_user;
    private PNPGroup $_group;

    protected function setUp(): void
    {
        $this->Initialize();
    }

    /**
     * Test for uniqidReal method in InvitationTools
     * 
     * This method generates a unique ID using either the random_bytes or openssl_random_pseudo_bytes functions,
     * or throws an Exception if they're not available. The length of the ID can be customized.
     * The test will check that the length of the generated ID matches the provided length, and that an Exception is thrown
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
            $repoGroupInvitation = $this->_entityManager->getRepository(PNPGroupInvite::class);

            $this->createMocks();

            $invitationTool = new InvitationTools($this->_entityManager);

            // test Invite Creation only with PNPGroup
            $code = $invitationTool->createInvitation($this->_group);
            $this->assertNotEmpty($code);
            $this->assertIsString($code);
            $invite = $repoGroupInvitation->findOneBy([
                'inviteCode' => $code
            ]);
            $this->assertInstanceOf(PNPGroupInvite::class, $invite);
            $this->assertEquals($this->_group, $invite->getInviteGroup());
            $this->assertNull($invite->getInvitedUser());
            $this->assertInstanceOf(DateTimeInterface::class, $invite->getInviteDate());

            // test Invite Creation with PNPGroup & PNPUser
            $code = $invitationTool->createInvitation($this->_group, $this->_user);
            $this->assertNotEmpty($code);
            $this->assertIsString($code);
            $invite = $repoGroupInvitation->findOneBy([
                'inviteCode' => $code
            ]);
            $this->assertInstanceOf(PNPGroupInvite::class, $invite);
            $this->assertEquals($this->_group, $invite->getInviteGroup());
            $this->assertEquals($this->_user, $invite->getInvitedUser());
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

            $invitationTool = new InvitationTools($this->_entityManager);

            $code = $invitationTool->createInvitation($this->_group);
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
            $character->setUser($this->_user);
            $character->setName("Test");
            $character->setRuleSet($this->_ruleset);

            $this->_entityManager->persist($character);
            $this->_entityManager->flush();

            $invitationTool = new InvitationTools($this->_entityManager);
            $code = $invitationTool->createInvitation($this->_group);
            $invitationTool->redeemInvitationCode($code, $character);

            $this->assertEquals($this->_group, $character->getPnpGroup());

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
            $character->setUser($this->_user);
            $character->setName("Test");
            $character->setRuleSet($this->_ruleset);

            $this->_entityManager->persist($character);
            $this->_entityManager->flush();

            $invitationTool = new InvitationTools($this->_entityManager);
            $code = $invitationTool->createInvitation($this->_group, $this->_user);
            $invitationTool->redeemInvitationCode($code, $character);

            $this->assertEquals($this->_group, $character->getPnpGroup());

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
            $this->_entityManager->persist($wrongUser);
            $this->_entityManager->flush();

            $character = new CharacterData();
            $character->setUser($wrongUser);
            $character->setName("Test");
            $character->setRuleSet($this->_ruleset);

            $this->_entityManager->persist($character);
            $this->_entityManager->flush();

            $invitationTool = new InvitationTools($this->_entityManager);
            $code = $invitationTool->createInvitation($this->_group, $this->_user);
            $invitationTool->redeemInvitationCode($code, $character);

            $this->assertNotEquals($this->_group, $character->getPnpGroup());

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

            $repoInvites = $this->_entityManager->getRepository(PNPGroupInvite::class);

            $wrongUser = new PNPUser();
            $wrongUser->setUsername("test2");
            $wrongUser->setPassword("test2");
            $this->_entityManager->persist($wrongUser);
            $this->_entityManager->flush();

            $character = new CharacterData();
            $character->setUser($wrongUser);
            $character->setName("Test");
            $character->setRuleSet($this->_ruleset);

            $this->_entityManager->persist($character);
            $this->_entityManager->flush();

            $invitationTool = new InvitationTools($this->_entityManager);
            $code = $invitationTool->createInvitation($this->_group, $this->_user);

            $invite = $repoInvites->findOneBy([
                'inviteCode' => $code
            ]);

            $invite->setInviteDate(new DateTime('1983-12-19 00:00:00'));

            $invitationTool->redeemInvitationCode($code, $character);

            $this->assertNotEquals($this->_group, $character->getPnpGroup());

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
        $this->_ruleset = new RuleSet();
        $this->_ruleset->setName("Test");
        $this->_entityManager->persist($this->_ruleset);
        $this->_entityManager->flush();

        $this->_user = new PNPUser();
        $this->_user->setUsername("test");
        $this->_user->setPassword("test");
        $this->_entityManager->persist($this->_user);
        $this->_entityManager->flush();

        $this->_group = new PNPGroup();
        $this->_group->setName("test group");
        $this->_group->setDescription("test description");
        $this->_group->setRuleSet($this->_ruleset);
        $this->_group->setGameMaster($this->_user);
        $this->_entityManager->persist($this->_group);
        $this->_entityManager->flush();
    }
}