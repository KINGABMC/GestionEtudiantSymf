<?php

namespace App\EmplyeListDto;
use App\Entity\Departement;

use App\Entity\Employe;
use DateTimeImmutable;

class EmployeListDto
{
    public ?int $id;
    public ?string $nomComplet;
    public ?string $telephone;
    public bool $isArchived;
    public ?string $adresse;
    public string $departementName;
    public DateTimeImmutable $embaucheAt;
//mappers
    public static function fromEntitie(Employe $entity): EmployeListDto
    {
        $dto = new EmployeListDto();
        $dto->id = $entity->getId();
        $dto->nomComplet = $entity->getNomComplet();
        $dto->telephone = $entity->getTelephone();
        $dto->isArchived = $entity->getIsArchived();
        $dto->adresse = $entity->getAdresse();
        $dto->departementName = $entity->getDepartement() ? $entity->getDepartement()->getName() : 'N/A';
        $dto->embaucheAt = $entity->getEmbaucheAt();

        return $dto;
    }
    public static function fromEntities(array $entities): array
    {
        return array_map(function (Employe $entity) {
            return self::fromEntitie($entity);
        }, $entities);
       
    }
}