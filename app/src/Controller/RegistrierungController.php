<?php

namespace App\Controller;

use App\Entity\PNPUser;
use App\Traits\ControllerEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrierungController extends AbstractController
{
    use ControllerEntityManager;

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $this->setEntityManager($entityManager);

        $regForm = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Spieler'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Passwort'],
                'second_options' => ['label' => 'Passwort wiederholen'],
            ])
            ->add('Registrieren', SubmitType::class)
            ->getForm()
            ;

        $regForm->handleRequest($request);

        if ($regForm->isSubmitted() && $regForm->isValid()) {
            $userData = $regForm->getData();

            $newUser = new PNPUser();
            $newUser->setUsername($userData['username']);

            $newUser->setPassword(
                $passwordHasher->hashPassword($newUser, $userData['password'])
            );

            $entityManager->persist($newUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registrierung/index.html.twig', [
            'regForm' => $regForm->createView()
        ]);
    }
}
