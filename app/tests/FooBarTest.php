<?php

namespace App\Tests;

use App\Entity\Test;
use App\Tools\Tests\AbstractKernelTest;
use App\Tools\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FooBarTest extends AbstractKernelTest
{
    protected function setUp(): void
    {
        $this->initialize();
    }

    public function testSomething()
    {

        $test = new Test();

        $test->setName("Test");
        $this->entityManager->persist($test);
        $this->entityManager->flush();

        $repo = $this->entityManager->getRepository(Test::class);

        $test2 = $repo->findOneBy(["name" => "Test"]);

        $this->assertEquals("Test", $test2->getName());
    }
}
