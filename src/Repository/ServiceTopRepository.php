<?php

namespace App\Repository;

use App\Entity\ServiceTop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceTop>
 *
 * @method ServiceTop|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceTop|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceTop[]    findAll()
 * @method ServiceTop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceTopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceTop::class);
    }

    public function save(ServiceTop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ServiceTop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ServiceTop[] Returns an array of ServiceTop objects
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

//    public function findOneBySomeField($value): ?ServiceTop
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
