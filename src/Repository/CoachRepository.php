<?php

namespace App\Repository;

use App\Entity\Coach;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coach>
 */
class CoachRepository extends ServiceEntityRepository
{
    const cantidadResultados = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coach::class);
    }

    public function countCoaches(string $filter1 = null, string $filter2 = null): int{

        $qb = $this->createQueryBuilder('e')
            ->select('count(e.id)');

        if($filter1 == "hasSalary" || $filter2 == 'hasSalary'){
            $qb->andWhere($qb->expr()->isNotNull('e.salary'));
        }else if($filter1 == "!hasSalary" || $filter2 == '!hasSalary'){
            $qb->andWhere($qb->expr()->isNull('e.salary'));
        }

        return ceil($qb->getQuery()->getSingleScalarResult() / self::cantidadResultados);
    }

    public function listCoaches(int $page, string $filter1 = null, string $filter2 = null): array{

        $qb = $this->createQueryBuilder('co')
            ->select("co.id", "co.name", "co.salary", "cl.name as club", "cl.id as clubId")
            ->leftJoin("co.club", "cl")
            ->setFirstResult(($page - 1) * self::cantidadResultados)
            ->setMaxResults(self::cantidadResultados);

        if($filter1 == "hasSalary" || $filter2 == 'hasSalary'){
            $qb->andWhere($qb->expr()->isNotNull('co.salary'));
        }else if($filter1 == "!hasSalary" || $filter2 == '!hasSalary'){
            $qb->andWhere($qb->expr()->isNull('co.salary'));
        }

        switch($filter1){
            case 'names':               $qb -> addOrderBy('co.name', 'ASC'); break;
            case 'reversedNames':       $qb -> addOrderBy('co.name', 'DESC'); break;
            case 'salarys':             $qb -> addOrderBy('co.salary', 'ASC'); break;
            case 'reversedSalarys':     $qb -> addOrderBy('co.salary', 'DESC'); break;
        }

        switch($filter2){
            case 'names':               $qb -> addOrderBy('co.name', 'ASC'); break;
            case 'reversedNames':       $qb -> addOrderBy('co.name', 'DESC'); break;
            case 'salarys':             $qb -> addOrderBy('co.salary', 'ASC'); break;
            case 'reversedSalarys':     $qb -> addOrderBy('co.salary', 'DESC'); break;
        }

        return $qb->getQuery()->getArrayResult();
    }

}
