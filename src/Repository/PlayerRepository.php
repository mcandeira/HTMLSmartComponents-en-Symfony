<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private SerializerInterface $serializer
    )
    {
        parent::__construct($registry, Player::class);
    }

    public function listPlayers(int $page): string{

        $cantidadResultados = 10;

        $players = $this -> createQueryBuilder('p')
            ->setFirstResult(($page - 1) * $cantidadResultados)
            ->setMaxResults($cantidadResultados)
            ->getQuery()
            ->getResult();

        return $this->serializer->serialize($players, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'salary', 'club' => ['id', 'name']]]);
    }

    //    /**
    //     * @return Player[] Returns an array of Player objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Player
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
