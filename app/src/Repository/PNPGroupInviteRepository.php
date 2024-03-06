<?php

namespace App\Repository;

use App\Entity\PNPGroupInvite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PNPGroupInvite>
 *
 * @method PNPGroupInvite|null find($id, $lockMode = null, $lockVersion = null)
 * @method PNPGroupInvite|null findOneBy(array $criteria, array $orderBy = null)
 * @method PNPGroupInvite[]    findAll()
 * @method PNPGroupInvite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PNPGroupInviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PNPGroupInvite::class);
    }

//    /**
//     * @return PNPGroupInvite[] Returns an array of PNPGroupInvite objects
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

//    public function findOneBySomeField($value): ?PNPGroupInvite
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
