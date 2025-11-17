<?php

namespace App\DataFixtures;

use App\Repository\DepartementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeFixtures extends Fixture
{
    public function __construct(private readonly DepartementRepository $departementRepository,private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $departements = $this->departementRepository->findAll();
        foreach ($departements as $key=> $departement) {
          for ($i = 1; $i <= 10; $i++) {
            $data = new \App\Entity\Employe();
            $data->setNomComplet("Employe". $key."".$i);
            $data->setTelephone("07000000".$key."".$i);
            $data->setDepartement($departement);
            $date = new \DateTimeImmutable('2025-10-01');
            $newDate = $date->add(new \DateInterval('P'.$i.'D'));
            $data->setEmbaucheAt($newDate);
            $data->setEmail("employe".$key."".$i."@example.com");
            $HashedPassword = $this->passwordHasher->hashPassword(
                $data,
                'password123'
            );
            $data->setPassword($HashedPassword);
            $i === 1 ? $data->setRoles(['ROLE_ADMIN']) : $data->setRoles(['ROLE_EMPLOYE']);
            $manager->persist($data);
          }
        }

        $manager->flush();
    }
}
