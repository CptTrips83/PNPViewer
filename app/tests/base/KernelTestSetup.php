<?php

namespace App\Tests\base;

use App\Tools\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class KernelTestSetup extends KernelTestCase
{
    protected EntityManagerInterface $_entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);

        $this->_entityManager = $kernel->getContainer()->get('doctrine', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)->getManager();
    }
}