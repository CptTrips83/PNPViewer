<?php

namespace App\Repository;

use App\Entity\CharacterStatsCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterStatsCategory>
 *
 * @method CharacterStatsCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterStatsCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterStatsCategory[]    findAll()
 * @method CharacterStatsCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterStatsCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterStatsCategory::class);
    }

//    /**
//     * @return CharacterStatsCategory[] Returns an array of CharacterStatsCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CharacterStatsCategory
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
