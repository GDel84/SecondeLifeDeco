<?php

namespace App\Repository;

use App\Entity\AccueilCenter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccueilCenter>
 *
 * @method AccueilCenter|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccueilCenter|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccueilCenter[]    findAll()
 * @method AccueilCenter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccueilCenterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccueilCenter::class);
    }

    public function save(AccueilCenter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AccueilCenter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AccueilCenter[] Returns an array of AccueilCenter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AccueilCenter
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
