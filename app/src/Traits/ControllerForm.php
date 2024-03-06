<?php

namespace App\Traits;

use App\Form\CharacterStatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait ControllerForm
 *
 * This trait provides methods for processing forms in a controller.
 */
trait ControllerForm
{
    /**
     * Process a form.
     *
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param Request $request The request object.
     * @param string $entityClassName The fully qualified class name of the entity to be processed.
     * @param string $formClassName The fully qualified class name of the form to be created.
     * @param int $id The ID of the entity (optional, default value is 0).
     *
     * @return FormInterface The processed form.
     */
    private function processForm(EntityManagerInterface $entityManager,
                                 Request                $request,
                                 string                 $entityClassName,
                                 string                 $formClassName,
                                 int                    $id = 0) : FormInterface
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

    /**
     * Redirects to a given route if a form submission is completed and valid.
     *
     * @param FormInterface $form The form object to check.
     * @param string $redirect The route to redirect to on form completion.
     * @param array $parameter Optional parameters to pass to the redirect route.
     *
     * @return Response|null Returns a Response object if the form submission is completed and valid,
     *                      or null if the form is not yet completed or invalid.
     */
    private function redirectOnFormCompletion(FormInterface $form, string $redirect, array $parameter = []) : Response | null
    {
        if($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute($redirect, $parameter);
        }

        return null;
    }
}