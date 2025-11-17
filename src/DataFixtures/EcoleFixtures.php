<?php

namespace App\DataFixtures;

use App\Entity\Ecole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EcoleFixtures extends Fixture
{
    public const ECOLE_UCAD = 'ecole-ucad';
    public const ECOLE_ESP = 'ecole-esp';
    public const ECOLE_ISM = 'ecole-ism';
    public const ECOLE_UVS = 'ecole-uvs';
    public const ECOLE_ESEA = 'ecole-esea';

    public function load(ObjectManager $manager): void
    {
        $ecoles = [
            [
                'nom' => 'Université Cheikh Anta Diop (UCAD)',
                'type' => 'public',
                'presentation' => 'L\'UCAD est la plus grande université du Sénégal, offrant des formations dans tous les domaines.',
                'accreditations' => 'ANQEP, CAMES',
                'coutScolarite' => 25000,
                'tauxInsertion' => 65.5,
                'adresse' => 'Avenue Cheikh Anta Diop',
                'ville' => 'Dakar',
                'region' => 'Dakar',
                'telephone' => '338242500',
                'email' => 'contact@ucad.sn',
                'siteWeb' => 'https://www.ucad.sn',
                'estVerifiee' => true,
                'reference' => self::ECOLE_UCAD
            ],
            [
                'nom' => 'École Supérieure Polytechnique (ESP)',
                'type' => 'public',
                'presentation' => 'Grande école d\'ingénieurs formant aux métiers techniques et technologiques.',
                'accreditations' => 'CAMES, CTI',
                'coutScolarite' => 50000,
                'tauxInsertion' => 85.0,
                'adresse' => 'Corniche Ouest',
                'ville' => 'Dakar',
                'region' => 'Dakar',
                'telephone' => '338259900',
                'email' => 'esp@esp.sn',
                'siteWeb' => 'https://www.esp.sn',
                'estVerifiee' => true,
                'reference' => self::ECOLE_ESP
            ],
            [
                'nom' => 'Institut Supérieur de Management (ISM)',
                'type' => 'prive',
                'presentation' => 'École privée de commerce et de management reconnue pour son excellence.',
                'accreditations' => 'ANQEP',
                'coutScolarite' => 1500000,
                'tauxInsertion' => 78.5,
                'adresse' => 'Route de la Corniche Ouest',
                'ville' => 'Dakar',
                'region' => 'Dakar',
                'telephone' => '338692300',
                'email' => 'contact@ism.sn',
                'siteWeb' => 'https://www.ism.edu.sn',
                'estVerifiee' => true,
                'reference' => self::ECOLE_ISM
            ],
            [
                'nom' => 'Université Virtuelle du Sénégal (UVS)',
                'type' => 'public',
                'presentation' => 'Université entièrement en ligne offrant des formations diplômantes.',
                'accreditations' => 'ANQEP',
                'coutScolarite' => 100000,
                'tauxInsertion' => 55.0,
                'adresse' => 'Diamniadio',
                'ville' => 'Diamniadio',
                'region' => 'Dakar',
                'telephone' => '338590000',
                'email' => 'contact@uvs.sn',
                'siteWeb' => 'https://www.uvs.sn',
                'estVerifiee' => true,
                'reference' => self::ECOLE_UVS
            ],
            [
                'nom' => 'École Supérieure d\'Économie Appliquée (ESEA)',
                'type' => 'prive',
                'presentation' => 'Formation en économie, gestion et finance avec des programmes pratiques.',
                'accreditations' => 'ANQEP',
                'coutScolarite' => 950000,
                'tauxInsertion' => 72.0,
                'adresse' => 'Sacré Coeur 3',
                'ville' => 'Dakar',
                'region' => 'Dakar',
                'telephone' => '338654321',
                'email' => 'info@esea.sn',
                'siteWeb' => 'https://www.esea.sn',
                'estVerifiee' => true,
                'reference' => self::ECOLE_ESEA
            ],
        ];

        foreach ($ecoles as $data) {
            $ecole = new Ecole();
            $ecole->setNom($data['nom']);
            $ecole->setType($data['type']);
            $ecole->setPresentation($data['presentation']);
            $ecole->setAccreditations($data['accreditations']);
            $ecole->setCoutScolarite((string)$data['coutScolarite']);
            $ecole->setTauxInsertion((string)$data['tauxInsertion']);
            $ecole->setAdresse($data['adresse']);
            $ecole->setVille($data['ville']);
            $ecole->setRegion($data['region']);
            $ecole->setTelephone($data['telephone']);
            $ecole->setEmail($data['email']);
            $ecole->setSiteWeb($data['siteWeb']);
            $ecole->setEstVerifiee($data['estVerifiee']);
            $ecole->setEstActive(true);

            $manager->persist($ecole);
            $this->addReference($data['reference'], $ecole);
        }

        $manager->flush();
    }
}
