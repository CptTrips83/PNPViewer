<?php

namespace App\Tests\Tools\PNPGroup;

use App\Entity\PNPGroup;
use App\Entity\PNPGroupInvite;
use App\Entity\PNPUser;
use App\Tools\Tests\AbstractWebTest;
use Symfony\Component\DomCrawler\Link;

class InvitationWebTest extends AbstractWebTest
{
    protected function setUp(): void
    {
        $this->initialize();
        parent::setUp();
    }

    public function testGroupInvitation() :void
    {
        $user = $this->entityManager->getRepository(PNPUser::class)
            ->find(1);
        $group = $this->entityManager->getRepository(PNPGroup::class)
            ->find(1);

        $this->client->loginUser($user);
        $this->client->request(
            'GET',
            "/group/invitation/generate/{$group->getId()}/{$user->getId()}"
        );
        $this->assertResponseRedirects();

        $this->assertCount(1, $group->getInvites());

        /** @var PNPGroupInvite $invitation */
        $invitation = $group->getInvites()[0];

        $this->client->request(
            'GET',
            "/group/invitation/show/{$invitation->getInviteCode()}"
        );

        $contains = (strpos($this->client->getResponse()->getContent(), $invitation->getInviteCode()) != 0);

        $this->assertTrue($contains);

        $this->client->request(
            'GET',
            "/group/invitation/redeem/{$invitation->getInviteCode()}"
        );

        $this->client->getResponse();

        $contains = (strpos($this->client->getResponse()->getContent(), "Darius") != 0);

        $this->assertTrue($contains);
    }
}
