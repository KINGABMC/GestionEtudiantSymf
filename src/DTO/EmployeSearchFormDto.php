<?php
namespace App\Service;

use App\Entity\Departement;
use App\Entity\Employe; 
use \DateTimeImmutable;
class EmployeSearchFormDto
{
    public ?string $name = null;
    public ?string $telephone = null;
    public ?DateTimeImmutable $embaucheAt = null;
    public ?Departement $departement = null;
}