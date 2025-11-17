<?php

namespace App\Repository;

use App\Entity\Ecole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EcoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ecole::class);
    }

    public function save(Ecole $ecole, bool $flush = false): void
    {
        $this->getEntityManager()->persist($ecole);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findActives(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.estActive = :active')
            ->setParameter('active', true)
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(array $criteria): array
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.estActive = :active')
            ->setParameter('active', true);

        if (isset($criteria['type']) && $criteria['type']) {
            $qb->andWhere('e.type = :type')
               ->setParameter('type', $criteria['type']);
        }

        if (isset($criteria['ville']) && $criteria['ville']) {
            $qb->andWhere('e.ville LIKE :ville')
               ->setParameter('ville', '%' . $criteria['ville'] . '%');
        }

        if (isset($criteria['region']) && $criteria['region']) {
            $qb->andWhere('e.region = :region')
               ->setParameter('region', $criteria['region']);
        }

        if (isset($criteria['search']) && $criteria['search']) {
            $qb->andWhere('e.nom LIKE :search OR e.presentation LIKE :search')
               ->setParameter('search', '%' . $criteria['search'] . '%');
        }

        return $qb->orderBy('e.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }
}
