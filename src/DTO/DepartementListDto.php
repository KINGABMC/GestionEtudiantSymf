<?php

namespace App\DTO;
use App\Entity\Departement;
use DateTimeImmutable;

class DepartementListDto
{
    public int $id;
    public string $name;
    public ?bool $isArchived = null;
    public int $nbrEmployes = 0;
    public DateTimeImmutable $createAt;

    public static function fromEntitie(Departement $entity): DepartementListDto
    {
        $dto = new DepartementListDto();
        $dto->id = $entity->getId();
        $dto->name = $entity->getName();
        $dto->isArchived = $entity->getIsArchived();
        $dto->createAt = $entity->getcreateAt();
        $dto->nbrEmployes = count($entity->getEmployes());

        return $dto;
    }
public static function fromEntities(array $entities): array
    {
        return array_map(function (Departement $entity) {
            return self::fromEntitie($entity);
        }, $entities);
       
    }
}
