<?php

namespace App\Repository;

use App\Entity\TypeBac;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TypeBacRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeBac::class);
    }

    public function save(TypeBac $typeBac, bool $flush = false): void
    {
        $this->getEntityManager()->persist($typeBac);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findActifs(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.estActif = :actif')
            ->setParameter('actif', true)
            ->orderBy('t.libelle', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
