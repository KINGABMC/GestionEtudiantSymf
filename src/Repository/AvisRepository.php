<?php

namespace App\Repository;

use App\Entity\Avis;
use App\Entity\Ecole;
use App\Entity\Filiere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    public function save(Avis $avis, bool $flush = false): void
    {
        $this->getEntityManager()->persist($avis);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findPubliesForEcole(Ecole $ecole): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.ecole = :ecole')
            ->andWhere('a.estPublie = :publie')
            ->setParameter('ecole', $ecole)
            ->setParameter('publie', true)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPubliesForFiliere(Filiere $filiere): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.filiere = :filiere')
            ->andWhere('a.estPublie = :publie')
            ->setParameter('filiere', $filiere)
            ->setParameter('publie', true)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
