<?php

namespace App\Controller;

use App\Entity\PNPUser;
use App\Entity\PNPUserPWResetRequest;
use App\Tools\UniqueID;
use App\Traits\ControllerEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mail', name: 'app_mail')]
class PNPResetPWMailController extends AbstractController
{
    use ControllerEntityManager;

    #[Route('/user/reset/pw', name: '.user.reset.pw')]
    function prompt(
        EntityManagerInterface $entityManager,
        Request $request
    ) : Response
    {
        $form = $this->createFormBuilder()
            ->add('email',EmailType::class, [
                'label' => 'E-Mail Adresse'
            ])
            ->add('pwReset', SubmitType::class, [
                'label' => 'Passwort zurücksetzen'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = $data['email'];

            $user = $this->_entityManager->getRepository(PNPUser::class)
                ->findOneBy([
                    'email' => $email
                ]);

                if (!$user) {
                    // Handle user not found error
                    return $this->redirectToRoute('app_mail.user.reset.pw');
                }

                $resetRequest = new PNPUserPWResetRequest();
                $resetRequest->setCreated(new \DateTime());
                $resetRequest->setCode(UniqueID::uniqueIdInt());
                $resetRequest->setUser($user);

                $this->_entityManager->persist($resetRequest);
                $this->_entityManager->flush();

                return $this->redirectToRoute(
                    'app_mail.user.reset.send',
                    $user->getId()
                );
        }

        return $this->render('pnp_user_mail/pwResetPrompt.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/reset/send/{userId}', name: '.user.reset.send')]
    public function send(
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        int $userId
    ): Response
    {
        $this->setEntityManager($entityManager);

        $user = $this->_entityManager->getRepository(PNPUser::class)
            ->find($userId);

        $resetRequest = $this->_entityManager->getRepository(PNPUserPWResetRequest::class)
            ->findOneBy([
                'user' => $user
            ]);

        $resetRequest ? $resetCode = $resetRequest->getCode() : $resetCode = UniqueID::uniqueIdInt();

        $mail = new TemplatedEmail();

        $mail->to($user->getEmail())
            ->from("pnp@rentner-coding-nordheide.de")
            ->to($user->getEmail())
            ->subject("Pen & Paper Viewer Passwort zurücksetzen")
            ->htmlTemplate('mails/user/resetpw.html.twig')
            ->context([
                'user' => $user,
                'resetCode' => $resetCode
            ]);

        $mailer->send($mail);

        return $this->redirectToRoute('app_mail.user.reset.pw');
    }

    #[Route('/user/reset/landing/{userId}/{resetCode}', name: '.user.reset.landing')]
    public function landing(
        EntityManagerInterface $entityManager,
        int $userId,
        int $resetCode
    )
    {

    }
}
