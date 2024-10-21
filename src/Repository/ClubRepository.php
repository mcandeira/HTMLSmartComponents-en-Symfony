<?php

namespace App\Repository;

use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Club>
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function listPlayers(int $clubID, string $playerPropertie, string $condition, string $referenceValue, int $page): array{

        $cantidadResultados = 10;

        return $this -> createQueryBuilder('c')
            ->innerJoin('c.players', 'p')
            ->select('p.name', 'p.salary')
            ->andWhere('c.id = :clubID')
            ->setParameter('clubID', $clubID)
            ->andWhere("p.$playerPropertie $condition :value")
            ->setParameter('value', $referenceValue)
            ->setFirstResult(($page - 1) * $cantidadResultados)
            ->setMaxResults($cantidadResultados)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Club[] Returns an array of Club objects
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

    //    public function findOneBySomeField($value): ?Club
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
