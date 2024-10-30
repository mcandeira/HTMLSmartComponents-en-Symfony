<?php

namespace App\Repository;

use App\Entity\Coach;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @extends ServiceEntityRepository<Coach>
 */
class CoachRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private SerializerInterface $serializer
    )
    {
        parent::__construct($registry, Coach::class);
    }

    public function listCoaches(int $page): string{

        $cantidadResultados = 10;

        $coaches = $this -> createQueryBuilder('c')
            ->setFirstResult(($page - 1) * $cantidadResultados)
            ->setMaxResults($cantidadResultados)
            ->getQuery()
            ->getResult();

        return $this->serializer->serialize($coaches, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'salary', 'club' => ['id', 'name']]]);
    }

    //    /**
    //     * @return Coach[] Returns an array of Coach objects
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

    //    public function findOneBySomeField($value): ?Coach
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
