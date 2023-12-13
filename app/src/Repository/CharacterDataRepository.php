<?php

namespace App\Repository;

use App\Entity\CharacterData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterData>
 *
 * @method CharacterData|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterData|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterData[]    findAll()
 * @method CharacterData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterData::class);
    }

//    /**
//     * @return CharacterData[] Returns an array of CharacterData objects
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

//    public function findOneBySomeField($value): ?CharacterData
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
