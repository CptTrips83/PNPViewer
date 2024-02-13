<?php

namespace App\Traits;

use Doctrine\ORM\EntityManagerInterface;

trait ControllerEntityManager
{
    private EntityManagerInterface $_entityManager;

    private function setEntityManager(EntityManagerInterface $entityManager) : void
    {
        $this->_entityManager = $entityManager;
    }
}