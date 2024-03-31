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

        $mail->to($user->getEmail())
            ->from("pnp@rentner-coding-nordheide.de")
            ->to($user->getEmail())
            ->subject("Pen & Paper Viewer Passwort zurücksetzen")
            ->htmlTemplate('mails/user/resetpw.html.twig')
            ->context([
                'user' => $user
            ]);

        $mailer->send($mail);



        return new Response();
    }
}
