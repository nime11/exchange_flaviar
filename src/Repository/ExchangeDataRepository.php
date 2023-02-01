<?php

namespace App\Repository;

use App\Entity\ExchangeData;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExchangeData>
 *
 * @method ExchangeData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeData[]    findAll()
 * @method ExchangeData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeData::class);
    }

    public function add(ExchangeData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExchangeData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   
    public function get($id): ?ExchangeData
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id = p')
            ->setParameter('p', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
//    /**
//     * @return ExchangeData[] Returns an array of ExchangeData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function getYearData($year): array
    {
        $start_date = new DateTime($year.'-01-01');
        $end_date = new DateTime($year.'-12-31');
        return $this->createQueryBuilder('e')
            ->andWhere('e.date BETWEEN  :start AND :end')
            ->setParameter('start', $start_date)
            ->setParameter('end', $end_date)
            ->getQuery()
            ->getResult()
        ;
    }
}
