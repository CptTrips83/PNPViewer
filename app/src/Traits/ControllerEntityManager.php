<?php

namespace App\Traits;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait ControllerEntityManager.
 *
 * This trait provides methods for managing the entity manager.
 */
trait ControllerEntityManager
{
    private EntityManagerInterface $_entityManager;

    /**
     * Sets the entity manager.
     *
     * @param EntityManagerInterface $entityManager The entity manager instance.
     * @return void
     */
    private function setEntityManager(EntityManagerInterface $entityManager) : void
    {
        $this->_entityManager = $entityManager;
    }
}