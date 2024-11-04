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
    const cantidadResultados = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function countClubs(): int{

        $qb = $this->createQueryBuilder('e')
            ->select('count(e.id)');

        return ceil($qb->getQuery()->getSingleScalarResult() / self::cantidadResultados);
    }

    public function listClubs(int $page, string $filter1 = null, string $filter2 = null): array{

        $qb = $this->createQueryBuilder('c')
            ->select("c.id", "c.name", "c.budget")
            ->setFirstResult(($page - 1) * self::cantidadResultados)
            ->setMaxResults(self::cantidadResultados);

        switch($filter1){
            case 'names':               $qb -> addOrderBy('c.name', 'ASC'); break;
            case 'reversedNames':       $qb -> addOrderBy('c.name', 'DESC'); break;
            case 'budgets':             $qb -> addOrderBy('c.budget', 'ASC'); break;
            case 'reversedBudgets':     $qb -> addOrderBy('c.budget', 'DESC'); break;
        }

        switch($filter2){
            case 'names':               $qb -> addOrderBy('c.name', 'ASC'); break;
            case 'reversedNames':       $qb -> addOrderBy('c.name', 'DESC'); break;
            case 'budgets':             $qb -> addOrderBy('c.budget', 'ASC'); break;
            case 'reversedBudgets':     $qb -> addOrderBy('c.budget', 'DESC'); break;
        }

        return $qb->getQuery()->getArrayResult();
    }

}
