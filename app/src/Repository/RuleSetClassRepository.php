<?php

namespace App\Repository;

use App\Entity\RuleSetClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RuleSetClass>
 *
 * @method RuleSetClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method RuleSetClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method RuleSetClass[]    findAll()
 * @method RuleSetClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RuleSetClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RuleSetClass::class);
    }

//    /**
//     * @return RuleSetClass[] Returns an array of RuleSetClass objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RuleSetClass
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
