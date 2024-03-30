<?php

namespace App\Controller;

use App\Entity\PNPUser;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/mail', name: 'app_mail')]
class PNPUserMailController extends AbstractController
{
    #[Route('/user/reset/send', name: '.user.reset.send')]
    public function index(
        MailerInterface $mailer,
        #[CurrentUser] PNPUser $user
    ): Response
    {
        $mail = new TemplatedEmail();

        // TODO Mailing fertigstellen
        // Unit-Test
        // Template erstellen
        // Landing-Page für PW-Reset

        $mail->to($user->getEmail())
            ->subject("Pen & Paper Viewer Passwort zurücksetzen")
            ->textTemplate('mails/user/resetpw.html.twig')
            ->context([
                'user' => $user
            ]);



        return new Response();
    }
}
