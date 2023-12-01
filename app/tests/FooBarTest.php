<?php

use App\Entity\Test;
use App\Tools\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use const App\Repository\TestRepository;

class FooBarTest extends KernelTestCase
{
    private EntityManagerInterface $_entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);

        $this->_entityManager = $kernel->getContainer()->get('doctrine', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)->getManager();
    }

    public function testSomething()
    {

        $test = new Test();

        $test->setName("Test");
        $this->_entityManager->persist($test);
        $this->_entityManager->flush();

        $repo = $this->_entityManager->getRepository(Test::class);

        $test2 = $repo->findOneBy(["name" => "Test"]);

        $this->assertEquals("Test", $test2->getName());
    }
}