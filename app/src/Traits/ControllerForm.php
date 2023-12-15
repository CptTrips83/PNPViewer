<?php

namespace App\Traits;

use App\Form\CharacterStatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

            $this->createSuccessFlashMessage();
        }

        return $form;
    }

    private function redirectOnFormCompletion(FormInterface $form, string $redirect, array $parameter = []) : Response | null
    {
        if($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute($redirect, $parameter);
        }

        return null;
    }
}