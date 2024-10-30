<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    const cantidadResultados = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function countPlayers(string $filter1 = null, string $filter2 = null): int{

        $qb = $this->createQueryBuilder('e')
            ->select('count(e.id)');

        if($filter1 == "hasSalary" || $filter2 == 'hasSalary'){
            $qb->andWhere($qb->expr()->isNotNull('e.salary'));
        }else if($filter1 == "!hasSalary" || $filter2 == '!hasSalary'){
            $qb->andWhere($qb->expr()->isNull('e.salary'));
        }

        return ceil($qb->getQuery()->getSingleScalarResult() / self::cantidadResultados);
    }

    public function listPlayers(int $page, string $filter1 = null, string $filter2 = null): array{

        $qb = $this->createQueryBuilder('p')
            ->select("p.id", "p.name", "p.salary", "c.name as club", "c.id as clubId")
            ->leftJoin("p.club", "c")
            ->setFirstResult(($page - 1) * self::cantidadResultados)
            ->setMaxResults(self::cantidadResultados);

        if($filter1 == "hasSalary" || $filter2 == 'hasSalary'){
            $qb->andWhere($qb->expr()->isNotNull('p.salary'));
        }else if($filter1 == "!hasSalary" || $filter2 == '!hasSalary'){
            $qb->andWhere($qb->expr()->isNull('p.salary'));
        }

        switch($filter1){
            case 'names':               $qb -> addOrderBy('p.name', 'ASC'); break;
            case 'reversedNames':       $qb -> addOrderBy('p.name', 'DESC'); break;
            case 'salarys':             $qb -> addOrderBy('p.salary', 'ASC'); break;
            case 'reversedSalarys':     $qb -> addOrderBy('p.salary', 'DESC'); break;
        }

        switch($filter2){
            case 'names':               $qb -> addOrderBy('p.name', 'ASC'); break;
            case 'reversedNames':       $qb -> addOrderBy('p.name', 'DESC'); break;
            case 'salarys':             $qb -> addOrderBy('p.salary', 'ASC'); break;
            case 'reversedSalarys':     $qb -> addOrderBy('p.salary', 'DESC'); break;
        }

        return $qb->getQuery()->getArrayResult();
    }

}
