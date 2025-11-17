<?php

namespace App\Repository;

use App\Entity\Filiere;
use App\Entity\TypeBac;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FiliereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filiere::class);
    }

    public function save(Filiere $filiere, bool $flush = false): void
    {
        $this->getEntityManager()->persist($filiere);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findActives(): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.estActive = :active')
            ->setParameter('active', true)
            ->orderBy('f.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByTypeBac(TypeBac $typeBac): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.typeBacsAcceptes', 't')
            ->where('t.id = :typeBacId')
            ->andWhere('f.estActive = :active')
            ->setParameter('typeBacId', $typeBac->getId())
            ->setParameter('active', true)
            ->orderBy('f.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findCompatibles(TypeBac $typeBac, float $moyenne): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.typeBacsAcceptes', 't')
            ->where('t.id = :typeBacId')
            ->andWhere('f.estActive = :active')
            ->andWhere('f.moyenneMinimale <= :moyenne OR f.moyenneMinimale IS NULL')
            ->setParameter('typeBacId', $typeBac->getId())
            ->setParameter('active', true)
            ->setParameter('moyenne', $moyenne)
            ->orderBy('f.moyenneMinimale', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
