<?php

namespace App\Repository;

use App\Entity\CharacterStatCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterStatCategory>
 *
 * @method CharacterStatCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterStatCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterStatCategory[]    findAll()
 * @method CharacterStatCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterStatCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterStatCategory::class);
    }

//    /**
//     * @return CharacterStatCategory[] Returns an array of CharacterStatCategory objects
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

//    public function findOneBySomeField($value): ?CharacterStatCategory
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
