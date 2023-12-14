<?php

namespace App\Traits;

use App\Form\CharacterStatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

trait ControllerForm
{
    private function processForm(EntityManagerInterface $entityManager,
                                 Request $request,
                                 string $entityClassName,
                                 string $formClassName,
                                 int $id = 0) : FormInterface
    {
        $dataObj = new $entityClassName();

        if($id != 0)
        {
            $repo = $entityManager->getRepository($entityClassName);
            $dataObj = $repo->find($id);
        }

        $form = $this->createForm($formClassName, $dataObj);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dataObj = $form->getData();

            $entityManager->persist($dataObj);
            $entityManager->flush();

            $dataObj = new $entityClassName();
            $form = $this->createForm($formClassName, $dataObj);

            $this->createSuccessFlashMessage();
        }

        return $form;
    }
}