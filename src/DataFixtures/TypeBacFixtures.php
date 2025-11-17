<?php

namespace App\DataFixtures;

use App\Entity\TypeBac;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeBacFixtures extends Fixture
{
    public const TYPE_BAC_L = 'type-bac-l';
    public const TYPE_BAC_S = 'type-bac-s';
    public const TYPE_BAC_G = 'type-bac-g';
    public const TYPE_BAC_T = 'type-bac-t';

    public function load(ObjectManager $manager): void
    {
        $typesBac = [
            [
                'code' => 'BAC_L',
                'libelle' => 'Baccalauréat Littéraire (L)',
                'description' => 'Baccalauréat orienté vers les lettres, langues et sciences humaines',
                'reference' => self::TYPE_BAC_L
            ],
            [
                'code' => 'BAC_S',
                'libelle' => 'Baccalauréat Scientifique (S)',
                'description' => 'Baccalauréat orienté vers les mathématiques, physique, chimie et SVT',
                'reference' => self::TYPE_BAC_S
            ],
            [
                'code' => 'BAC_G',
                'libelle' => 'Baccalauréat en Gestion (G)',
                'description' => 'Baccalauréat orienté vers la gestion, comptabilité et économie',
                'reference' => self::TYPE_BAC_G
            ],
            [
                'code' => 'BAC_T',
                'libelle' => 'Baccalauréat Technique (T)',
                'description' => 'Baccalauréat orienté vers les techniques industrielles et technologiques',
                'reference' => self::TYPE_BAC_T
            ],
        ];

        foreach ($typesBac as $data) {
            $typeBac = new TypeBac();
            $typeBac->setCode($data['code']);
            $typeBac->setLibelle($data['libelle']);
            $typeBac->setDescription($data['description']);
            $typeBac->setEstActif(true);

            $manager->persist($typeBac);
            $this->addReference($data['reference'], $typeBac);
        }

        $manager->flush();
    }
}
