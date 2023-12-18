<?php

namespace App\Repository;

use App\Entity\PNPGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PNPGroup>
 *
 * @method PNPGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PNPGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PNPGroup[]    findAll()
 * @method PNPGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PNPGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PNPGroup::class);
    }

//    /**
//     * @return PNPGroup[] Returns an array of PNPGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PNPGroup
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
