<?php

namespace App\Controller;

use App\Entity\PNPUser;
use App\Entity\PNPUserPWResetRequest;
use App\Tools\UniqueID;
use App\Traits\ControllerEntityManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mail', name: 'app_mail')]
class PNPResetPWMailController extends AbstractController
{
    const RESET_REQUEST_LIFESPAN = 3600;

    use ControllerEntityManager;

    #[Route('/user/reset/pw', name: '.user.reset.pw')]
    public function reset(
        EntityManagerInterface $entityManager,
        Request $request
    ) : Response {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'E-Mail Adresse'
            ])
            ->add('pwReset', SubmitType::class, [
                'label' => 'Passwort zurücksetzen'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = $data['email'];

            $user = $this->entityManager->getRepository(PNPUser::class)
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

                $this->entityManager->persist($resetRequest);
                $this->entityManager->flush();

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
    ): Response {
        $this->setEntityManager($entityManager);

        $user = $this->entityManager->getRepository(PNPUser::class)
            ->find($userId);

        $resetRequest = $this->entityManager->getRepository(PNPUserPWResetRequest::class)
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

    #[Route('/user/reset/prompt/{userId}/{resetCode}', name: '.user.reset.prompt')]
    public function prompt(
        EntityManagerInterface $entityManager,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        int $userId,
        string $resetCode
    ) : Response {
        $this->setEntityManager($entityManager);

        $regForm = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Passwort'],
                'second_options' => ['label' => 'Passwort wiederholen'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Passwort speichern'
            ])
            ->getForm()
        ;

        $regForm->handleRequest($request);

        if ($regForm->isSubmitted() && $regForm->isValid()) {
            try {
                $userData = $regForm->getData();
                $user = $this->entityManager->getRepository(PNPUser::class)
                    ->findOneBy([
                        'id' => $userId
                    ]);

                $resetRequest = $this->entityManager->getRepository(PNPUserPWResetRequest::class)
                    ->findOneBy([
                        'user' => $user,
                        'code' => $resetCode
                    ]);

                $password = $userData['password'];

                $hashPassword = $passwordHasher->hashPassword(
                    $user,
                    $password
                );
                $this->processPWReset($user, $resetRequest, $hashPassword);
                $this->removePWResetRequest($user, $resetRequest);
            } catch (Exception $exception) {
                return new Response("Error Saving new Password {$exception->getMessage()}", "400");
            }

            return $this->redirectToRoute('app_login');
        }


        return $this->render('registrierung/pwReset.html.twig', [
            'regForm' => $regForm->createView()
        ]);
    }

    /**
     * Checks if a password reset request is valid for the given user.
     *
     * @param PNPUser|null $user The user attempting to reset their password.
     * @param PNPUserPWResetRequest|null $resetRequest The password reset request object.
     * @return bool Returns true if the reset request is valid for the given user, false otherwise.
     * @throws Exception If the reset request has expired.
     */
    private function checkRequest(?PNPUser $user, ?PNPUserPWResetRequest $resetRequest) : bool
    {
        if ((new DateTime())->getTimestamp() - $resetRequest->getCreated()->getTimestamp()
            > self::RESET_REQUEST_LIFESPAN) {
            throw new Exception("Resest Request is expired");
        }

        return $resetRequest?->getUser() === $user;
    }

    /**
     * @throws Exception
     */
    private function processPWReset(?PNPUser $user, ?PNPUserPWResetRequest $resetRequest, string $newPassword) : void
    {
        if (!$user) {
            throw new Exception("User not found");
        }
        if (!$resetRequest) {
            throw new Exception("Request not found");
        }
        if (!$this->checkRequest($user, $resetRequest)) {
            throw new Exception("No Request for User");
        }

        $user->setPassword($newPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function removePWResetRequest(PNPUser $user, PNPUserPWResetRequest $resetRequest) : void
    {
        $user->removePwResetRequest($resetRequest);
        $this->entityManager->flush();
        $this->entityManager->remove($resetRequest);
        $this->entityManager->flush();
    }
}
