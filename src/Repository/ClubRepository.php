<?php

namespace App\Repository;

use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @extends ServiceEntityRepository<Club>
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private SerializerInterface $serializer
    )
    {
        parent::__construct($registry, Club::class);
    }

    public function listClubs(int $page): string{

        $cantidadResultados = 10;

        $clubs = $this -> createQueryBuilder('c')
            ->setFirstResult(($page - 1) * $cantidadResultados)
            ->setMaxResults($cantidadResultados)
            ->getQuery()
            ->getResult();

        return $this->serializer->serialize($clubs, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'budget', ]]);
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
