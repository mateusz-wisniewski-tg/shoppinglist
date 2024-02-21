<?php

namespace App\Repository;

use App\Entity\Sharee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sharee>
 *
 * @method Sharee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sharee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sharee[]    findAll()
 * @method Sharee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShareeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sharee::class);
    }

//    /**
//     * @return Sharee[] Returns an array of Sharee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sharee
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
