<?php

namespace App\Repository;

use App\Entity\Taxation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Taxation>
 *
 * @method Taxation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taxation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taxation[]    findAll()
 * @method Taxation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taxation::class);
    }

//    /**
//     * @return Taxation[] Returns an array of Taxation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Taxation
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
