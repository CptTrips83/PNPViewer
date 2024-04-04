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
        $this->initialize();
    }

    public function testMailIsSentAndContentIsOk(): void
    {
        $user = $this->entityManager->getRepository(PNPUser::class)
            ->find(1);

        $userPWRequest = new PNPUserPWResetRequest();
        $userPWRequest->setUser($user);
        $userPWRequest->setCode("11111");
        $userPWRequest->setCreated(new DateTime());

        $this->entityManager->persist($userPWRequest);
        $this->entityManager->flush();

        $this->client->loginUser($user);
        $this->client->request('GET', '/mail/user/reset/send/1');
        $this->assertResponseRedirects();

        $this->assertEmailCount(1); // use assertQueuedEmailCount() when using Messenger

        $email = $this->getMailerMessage();

        $this->assertEmailHtmlBodyContains($email, '11111');
        $this->assertEmailTextBodyContains($email, $user->getUsername());
    }
}
