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
        $this->Initialize();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testGroupInvitation() :void
    {
        $user = $this->_entityManager->getRepository(PNPUser::class)
            ->find(1);
        $group = $this->_entityManager->getRepository(PNPGroup::class)
            ->find(1);

        $this->_client->loginUser($user);
        $this->_client->request('GET',
            "/group/invitation/generate/{$group->getId()}/{$user->getId()}");
        $this->assertResponseRedirects();

        $this->assertCount(1, $group->getInvites());

        /** @var PNPGroupInvite $invitation */
        $invitation = $group->getInvites()[0];

        $this->_client->request('GET',
            "/group/invitation/show/{$invitation->getInviteCode()}");

        $contains = (strpos($this->_client->getResponse()->getContent(), $invitation->getInviteCode()) != 0);

        $this->assertTrue($contains);

        // TODO Redeem Test
        $this->_client->request('GET',
            "/group/invitation/redeem/{$invitation->getInviteCode()}");

        $this->_client->getResponse();

        $contains = (strpos($this->_client->getResponse()->getContent(), "Darius") != 0);

        $this->assertTrue($contains);
    }


}