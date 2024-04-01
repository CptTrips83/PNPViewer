<?php

namespace App\Repository;

use App\Entity\PNPUserPWResetRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PNPUserPWResetRequest>
 *
 * @method PNPUserPWResetRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PNPUserPWResetRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PNPUserPWResetRequest[]    findAll()
 * @method PNPUserPWResetRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PNPUserPWRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PNPUserPWResetRequest::class);
    }

    //    /**
    //     * @return PNPUserPWResetRequest[] Returns an array of PNPUserPWResetRequest objects
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

    //    public function findOneBySomeField($value): ?PNPUserPWResetRequest
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
