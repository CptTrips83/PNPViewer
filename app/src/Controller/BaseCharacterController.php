<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseCharacterController extends AbstractController
{
    #[Route('/base/character', name: 'app_base_character')]
    public function index(EntityManagerInterface $entityManager, CharacterRepository $repo): Response
    {
        $data = $repo->findOneBy(["name" => "Darius"]);

        return $this->render('base_character/index.html.twig', [
            'controller_name' => 'BaseCharacterController',
            'data' => $data
        ]);
    }
}
