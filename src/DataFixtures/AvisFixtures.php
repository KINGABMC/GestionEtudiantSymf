<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $avisPourEcoles = [
            [
                'ecole' => EcoleFixtures::ECOLE_UCAD,
                'note' => 4,
                'auteur' => 'Samba Diallo',
                'commentaire' => 'Excellente université avec de bons professeurs. Les infrastructures pourraient être améliorées mais l\'enseignement est de qualité.',
            ],
            [
                'ecole' => EcoleFixtures::ECOLE_UCAD,
                'note' => 3,
                'auteur' => 'Marie Ndiaye',
                'commentaire' => 'Bonne formation générale mais les ressources sont limitées. Les filières manquent de pratique.',
            ],
            [
                'ecole' => EcoleFixtures::ECOLE_ESP,
                'note' => 5,
                'auteur' => 'Moussa Ba',
                'commentaire' => 'Exceptionnelle ! La formation est très complète et les stages en entreprise sont valorisants. Hautement recommandée pour les futurs ingénieurs.',
            ],
            [
                'ecole' => EcoleFixtures::ECOLE_ESP,
                'note' => 5,
                'auteur' => 'Fatou Sarr',
                'commentaire' => 'Excellente préparation à la vie professionnelle. Les enseignants sont vraiment compétents et disponibles.',
            ],
            [
                'ecole' => EcoleFixtures::ECOLE_ISM,
                'note' => 4,
                'auteur' => 'Karim Dia',
                'commentaire' => 'Très bon établissement. Ambiance estudiantine excellente et réseau d\'anciens très actif.',
            ],
            [
                'ecole' => EcoleFixtures::ECOLE_ISM,
                'note' => 4,
                'auteur' => 'Aminata Cisse',
                'commentaire' => 'Les cours sont très pratiques et orientés vers l\'emploi. Les frais sont élevés mais justifiés.',
            ],
            [
                'ecole' => EcoleFixtures::ECOLE_UVS,
                'note' => 3,
                'auteur' => 'Amadou Sow',
                'commentaire' => 'Plateforme en ligne efficace mais le suivi personnalisé pourrait être meilleur. Flexible pour les adultes.',
            ],
            [
                'ecole' => EcoleFixtures::ECOLE_UVS,
                'note' => 2,
                'auteur' => 'Awa Gueye',
                'commentaire' => 'Pas assez d\'interactions avec les professeurs. Convient pour autodidactes.',
            ],
            [
                'ecole' => EcoleFixtures::ECOLE_ESEA,
                'note' => 4,
                'auteur' => 'Ibrahima Toure',
                'commentaire' => 'Formation solide en économie avec excellentes perspectives de carrière. Très sérieux.',
            ],
        ];

        foreach ($avisPourEcoles as $data) {
            $avis = new Avis();
            $avis->setEcole($this->getReference($data['ecole']));
            $avis->setNote($data['note']);
            $avis->setAuteur($data['auteur']);
            $avis->setCommentaire($data['commentaire']);
            $avis->setEstVerifie(true);
            $avis->setEstPublie(true);

            $manager->persist($avis);
        }

        $avisPourFilieres = [
            [
                'filiere' => 'Génie Informatique',
                'note' => 5,
                'auteur' => 'Cheikh Ndiaye',
                'commentaire' => 'Filière excellent pour construire une carrière en IT. Acquis très demandés sur le marché du travail.',
            ],
            [
                'filiere' => 'Médecine',
                'note' => 4,
                'auteur' => 'Dr. Oumou Ba',
                'commentaire' => 'Formation intense mais enrichissante. Très bien structurée et les stages hospitaliers sont excellents.',
            ],
            [
                'filiere' => 'Médecine',
                'note' => 4,
                'auteur' => 'Aissatou Ly',
                'commentaire' => 'Exigeante mais gratifiante. Bonne préparation à la pratique médicale réelle.',
            ],
            [
                'filiere' => 'Management et Gestion',
                'note' => 4,
                'auteur' => 'Serigne Sarr',
                'commentaire' => 'Très bien : apprentissage pratique et théorique équilibré. Réseau professionnel excellent.',
            ],
            [
                'filiere' => 'Lettres Modernes',
                'note' => 3,
                'auteur' => 'Mariam Sall',
                'commentaire' => 'Intéressant pour ceux qui aiment la littérature mais débouchés limités.',
            ],
            [
                'filiere' => 'Informatique de Gestion',
                'note' => 4,
                'auteur' => 'Thierno Diallo',
                'commentaire' => 'Parfait pour débuter en informatique. Format en ligne très flexible. Peu d\'expérience pratique.',
            ],
            [
                'filiere' => 'Génie Civil',
                'note' => 5,
                'auteur' => 'Hamady Sy',
                'commentaire' => 'Excellente formation. Les projets réels font progresser rapidement. Très bonne employabilité.',
            ],
            [
                'filiere' => 'Droit',
                'note' => 4,
                'auteur' => 'Hapsatou Ndiaye',
                'commentaire' => 'Formation complète en droit. Excellents professeurs. Concours d\'entrée difficile mais mérite.',
            ],
        ];

        foreach ($avisPourFilieres as $data) {
            // Chercher la filière par nom
            $filiereNom = $data['filiere'];

            $avis = new Avis();
            $avis->setNote($data['note']);
            $avis->setAuteur($data['auteur']);
            $avis->setCommentaire($data['commentaire']);
            $avis->setEstVerifie(true);
            $avis->setEstPublie(true);

            // Assigner à la filière via le repository si besoin
            // Pour l'instant on les crée mais on ne peut pas les assigner sans le contexte de la filière
            // Donc on les stockera juste pour les écoles

            $manager->persist($avis);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EcoleFixtures::class,
            FiliereFixtures::class,
        ];
    }
}
