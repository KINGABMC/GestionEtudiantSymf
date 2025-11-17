<?php

namespace App\DataFixtures;

use App\Entity\Filiere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FiliereFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $filieres = [
            [
                'nom' => 'Génie Informatique',
                'ecole' => EcoleFixtures::ECOLE_ESP,
                'description' => 'Formation d\'ingénieurs en développement logiciel, réseaux et systèmes',
                'moyenneMinimale' => 12.0,
                'dureeAnnees' => 5,
                'coutAnnuel' => 50000,
                'debouches' => 'Ingénieur logiciel, Architecte système, Data scientist, Chef de projet IT',
                'documentsRequis' => 'Diplôme BAC, Certificat de naissance, Photos, Certificat médical',
                'concoursObligatoire' => true,
                'matieresImportantes' => 'Mathématiques, Physique, Informatique',
                'diplomeDelivre' => 'Diplôme d\'Ingénieur',
                'typesBac' => [TypeBacFixtures::TYPE_BAC_S, TypeBacFixtures::TYPE_BAC_T]
            ],
            [
                'nom' => 'Médecine',
                'ecole' => EcoleFixtures::ECOLE_UCAD,
                'description' => 'Formation médicale complète pour devenir médecin généraliste ou spécialiste',
                'moyenneMinimale' => 14.0,
                'dureeAnnees' => 7,
                'coutAnnuel' => 100000,
                'debouches' => 'Médecin généraliste, Médecin spécialiste, Chirurgien, Chercheur médical',
                'documentsRequis' => 'Diplôme BAC scientifique, Extrait de naissance, Certificat médical complet',
                'concoursObligatoire' => true,
                'matieresImportantes' => 'Biologie, Chimie, Physique, Mathématiques',
                'diplomeDelivre' => 'Doctorat en Médecine',
                'typesBac' => [TypeBacFixtures::TYPE_BAC_S]
            ],
            [
                'nom' => 'Management et Gestion',
                'ecole' => EcoleFixtures::ECOLE_ISM,
                'description' => 'Formation en management, marketing, finance et entrepreneuriat',
                'moyenneMinimale' => 10.5,
                'dureeAnnees' => 3,
                'coutAnnuel' => 1500000,
                'debouches' => 'Manager, Consultant, Entrepreneur, Responsable marketing, Contrôleur de gestion',
                'documentsRequis' => 'Diplôme BAC, Extrait de naissance, Photos',
                'concoursObligatoire' => false,
                'matieresImportantes' => 'Mathématiques, Économie, Français',
                'diplomeDelivre' => 'Licence en Management',
                'typesBac' => [TypeBacFixtures::TYPE_BAC_G, TypeBacFixtures::TYPE_BAC_L, TypeBacFixtures::TYPE_BAC_S]
            ],
            [
                'nom' => 'Lettres Modernes',
                'ecole' => EcoleFixtures::ECOLE_UCAD,
                'description' => 'Étude de la littérature française et francophone, linguistique et sciences du langage',
                'moyenneMinimale' => 10.0,
                'dureeAnnees' => 3,
                'coutAnnuel' => 25000,
                'debouches' => 'Enseignant, Journaliste, Écrivain, Traducteur, Attaché culturel',
                'documentsRequis' => 'Diplôme BAC, Certificat de naissance',
                'concoursObligatoire' => false,
                'matieresImportantes' => 'Français, Philosophie, Histoire',
                'diplomeDelivre' => 'Licence en Lettres Modernes',
                'typesBac' => [TypeBacFixtures::TYPE_BAC_L]
            ],
            [
                'nom' => 'Informatique de Gestion',
                'ecole' => EcoleFixtures::ECOLE_UVS,
                'description' => 'Formation en développement web, bases de données et systèmes d\'information',
                'moyenneMinimale' => 10.0,
                'dureeAnnees' => 3,
                'coutAnnuel' => 100000,
                'debouches' => 'Développeur web, Administrateur bases de données, Analyste programmeur',
                'documentsRequis' => 'Diplôme BAC, Photos, Certificat de naissance',
                'concoursObligatoire' => false,
                'matieresImportantes' => 'Mathématiques, Informatique',
                'diplomeDelivre' => 'Licence en Informatique',
                'typesBac' => [TypeBacFixtures::TYPE_BAC_S, TypeBacFixtures::TYPE_BAC_G, TypeBacFixtures::TYPE_BAC_T]
            ],
            [
                'nom' => 'Économie Appliquée',
                'ecole' => EcoleFixtures::ECOLE_ESEA,
                'description' => 'Formation en analyse économique, statistiques et politiques économiques',
                'moyenneMinimale' => 11.0,
                'dureeAnnees' => 3,
                'coutAnnuel' => 950000,
                'debouches' => 'Économiste, Analyste financier, Consultant en stratégie, Chargé d\'études',
                'documentsRequis' => 'Diplôme BAC, Extrait de naissance, Photos',
                'concoursObligatoire' => false,
                'matieresImportantes' => 'Mathématiques, Économie, Statistiques',
                'diplomeDelivre' => 'Licence en Économie',
                'typesBac' => [TypeBacFixtures::TYPE_BAC_G, TypeBacFixtures::TYPE_BAC_S]
            ],
            [
                'nom' => 'Génie Civil',
                'ecole' => EcoleFixtures::ECOLE_ESP,
                'description' => 'Formation d\'ingénieurs en construction, travaux publics et bâtiments',
                'moyenneMinimale' => 12.5,
                'dureeAnnees' => 5,
                'coutAnnuel' => 50000,
                'debouches' => 'Ingénieur BTP, Chef de projet construction, Bureau d\'études, Entrepreneur BTP',
                'documentsRequis' => 'Diplôme BAC, Certificat de naissance, Photos, Certificat médical',
                'concoursObligatoire' => true,
                'matieresImportantes' => 'Mathématiques, Physique, Dessin technique',
                'diplomeDelivre' => 'Diplôme d\'Ingénieur',
                'typesBac' => [TypeBacFixtures::TYPE_BAC_S, TypeBacFixtures::TYPE_BAC_T]
            ],
            [
                'nom' => 'Droit',
                'ecole' => EcoleFixtures::ECOLE_UCAD,
                'description' => 'Formation juridique en droit privé, public, des affaires et international',
                'moyenneMinimale' => 11.0,
                'dureeAnnees' => 4,
                'coutAnnuel' => 25000,
                'debouches' => 'Avocat, Magistrat, Juriste d\'entreprise, Notaire, Huissier',
                'documentsRequis' => 'Diplôme BAC, Extrait de naissance, Casier judiciaire',
                'concoursObligatoire' => false,
                'matieresImportantes' => 'Français, Histoire, Philosophie',
                'diplomeDelivre' => 'Master en Droit',
                'typesBac' => [TypeBacFixtures::TYPE_BAC_L, TypeBacFixtures::TYPE_BAC_G]
            ],
        ];

        foreach ($filieres as $data) {
            $filiere = new Filiere();
            $filiere->setNom($data['nom']);
            $filiere->setEcole($this->getReference($data['ecole']));
            $filiere->setDescription($data['description']);
            $filiere->setMoyenneMinimale((string)$data['moyenneMinimale']);
            $filiere->setDureeAnnees($data['dureeAnnees']);
            $filiere->setCoutAnnuel((string)$data['coutAnnuel']);
            $filiere->setDebouches($data['debouches']);
            $filiere->setDocumentsRequis($data['documentsRequis']);
            $filiere->setConcoursObligatoire($data['concoursObligatoire']);
            $filiere->setMatieresImportantes($data['matieresImportantes']);
            $filiere->setDiplomeDelivre($data['diplomeDelivre']);
            $filiere->setEstActive(true);

            foreach ($data['typesBac'] as $typeBacRef) {
                $filiere->addTypeBacsAccepte($this->getReference($typeBacRef));
            }

            $manager->persist($filiere);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EcoleFixtures::class,
            TypeBacFixtures::class,
        ];
    }
}
