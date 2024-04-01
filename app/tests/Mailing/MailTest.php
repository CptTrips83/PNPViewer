<?php

namespace App\Tests\Mailing;


use App\Entity\PNPUser;
use App\Entity\PNPUserPWResetRequest;
use App\Tools\Tests\AbstractWebTest;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

class MailTest extends AbstractWebTest
{
    protected function setUp(): void
    {
        $this->Initialize();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testMailIsSentAndContentIsOk(): void
    {
        $user = $this->_entityManager->getRepository(PNPUser::class)
            ->find(1);

        $userPWRequest = new PNPUserPWResetRequest();
        $userPWRequest->setUser($user);
        $userPWRequest->setCode("11111");
        $userPWRequest->setCreated(new DateTime());

        $this->_entityManager->persist($userPWRequest);
        $this->_entityManager->flush();

        $this->_client->loginUser($user);
        $this->_client->request('GET', '/mail/user/reset/send/1');
        $this->assertResponseRedirects();

        $this->assertEmailCount(1); // use assertQueuedEmailCount() when using Messenger

        $email = $this->getMailerMessage();

        $this->assertEmailHtmlBodyContains($email, '11111');
        $this->assertEmailTextBodyContains($email, $user->getUsername());
    }
}