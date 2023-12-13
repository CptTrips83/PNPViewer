<?php

namespace App\Tools\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractKernelTest extends KernelTestCase
{
    protected EntityManagerInterface $_entityManager;

    protected function Initialize() : void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);

        $this->_entityManager = $kernel->getContainer()->get('doctrine', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)->getManager();
    }
}