<?php

namespace App\Service;

use App\Entity\Bachelier;
use App\Entity\Filiere;
use App\Entity\TypeBac;
use App\Repository\FiliereRepository;

class ConseillerOrientationService
{
    public function __construct(
        private readonly FiliereRepository $filiereRepository
    ) {
    }

    public function recommanderFilieres(
        TypeBac $typeBac,
        float $moyenne,
        ?array $centresInteret = [],
        ?array $notesMatieresArray = []
    ): array {
        $filieres = $this->filiereRepository->findCompatibles($typeBac, $moyenne);

        $recommandations = [];

        foreach ($filieres as $filiere) {
            $score = $this->calculerScoreAffinite($filiere, $moyenne, $centresInteret, $notesMatieresArray);

            $recommandations[] = [
                'filiere' => $filiere,
                'score' => $score,
                'niveau' => $this->getNiveauRecommandation($score),
                'raisons' => $this->genererRaisons($filiere, $moyenne, $score),
                'risques' => $this->identifierRisques($filiere, $moyenne, $notesMatieresArray)
            ];
        }

        usort($recommandations, fn($a, $b) => $b['score'] <=> $a['score']);

        return $recommandations;
    }

    private function calculerScoreAffinite(
        Filiere $filiere,
        float $moyenne,
        array $centresInteret,
        array $notesMatieresArray
    ): float {
        $score = 0;

        $moyenneMin = $filiere->getMoyenneMinimale() ? (float)$filiere->getMoyenneMinimale() : 10.0;
        $ecartMoyenne = $moyenne - $moyenneMin;

        if ($ecartMoyenne >= 3) {
            $score += 40;
        } elseif ($ecartMoyenne >= 1.5) {
            $score += 30;
        } elseif ($ecartMoyenne >= 0) {
            $score += 20;
        } else {
            $score += 0;
        }

        if (!empty($notesMatieresArray)) {
            $matieresImportantes = $this->parseMatieresImportantes($filiere->getMatieresImportantes());
            $bonnesNotesCount = 0;

            foreach ($matieresImportantes as $matiere) {
                if (isset($notesMatieresArray[$matiere]) && $notesMatieresArray[$matiere] >= 12) {
                    $bonnesNotesCount++;
                }
            }

            if (count($matieresImportantes) > 0) {
                $score += ($bonnesNotesCount / count($matieresImportantes)) * 30;
            }
        }

        if (!empty($centresInteret)) {
            $motsClefs = $this->extraireMotsCles($filiere->getDescription() . ' ' . $filiere->getDebouches());
            $matchCount = 0;

            foreach ($centresInteret as $interet) {
                foreach ($motsClefs as $motClef) {
                    if (stripos($interet, $motClef) !== false || stripos($motClef, $interet) !== false) {
                        $matchCount++;
                        break;
                    }
                }
            }

            $score += min($matchCount * 10, 30);
        }

        return min($score, 100);
    }

    private function getNiveauRecommandation(float $score): string
    {
        if ($score >= 75) {
            return 'Fortement recommandé';
        } elseif ($score >= 60) {
            return 'Recommandé';
        } elseif ($score >= 40) {
            return 'Possible';
        } else {
            return 'Peu recommandé';
        }
    }

    private function genererRaisons(Filiere $filiere, float $moyenne, float $score): array
    {
        $raisons = [];

        $moyenneMin = $filiere->getMoyenneMinimale() ? (float)$filiere->getMoyenneMinimale() : 10.0;
        $ecart = $moyenne - $moyenneMin;

        if ($ecart >= 3) {
            $raisons[] = "Votre moyenne ({$moyenne}) dépasse largement le minimum requis ({$moyenneMin})";
        } elseif ($ecart >= 0) {
            $raisons[] = "Vous remplissez les critères de moyenne minimale";
        } else {
            $raisons[] = "Attention: votre moyenne est inférieure au minimum requis";
        }

        if ($score >= 75) {
            $raisons[] = "Excellente compatibilité avec votre profil";
        }

        if ($filiere->getEcole()->isEstVerifiee()) {
            $raisons[] = "École reconnue et accréditée";
        }

        $tauxInsertion = $filiere->getEcole()->getTauxInsertion();
        if ($tauxInsertion && (float)$tauxInsertion >= 70) {
            $raisons[] = "Bon taux d'insertion professionnelle ({$tauxInsertion}%)";
        }

        return $raisons;
    }

    private function identifierRisques(Filiere $filiere, float $moyenne, array $notesMatieresArray): array
    {
        $risques = [];

        $moyenneMin = $filiere->getMoyenneMinimale() ? (float)$filiere->getMoyenneMinimale() : 10.0;

        if ($moyenne < $moyenneMin) {
            $risques[] = "Moyenne insuffisante par rapport aux critères d'admission";
        }

        if ($filiere->isConcoursObligatoire()) {
            $risques[] = "Concours d'entrée obligatoire - préparation nécessaire";
        }

        $matieresImportantes = $this->parseMatieresImportantes($filiere->getMatieresImportantes());
        if (!empty($notesMatieresArray)) {
            foreach ($matieresImportantes as $matiere) {
                if (isset($notesMatieresArray[$matiere]) && $notesMatieresArray[$matiere] < 10) {
                    $risques[] = "Note faible en {$matiere}, matière importante pour cette filière";
                }
            }
        }

        $cout = $filiere->getCoutAnnuel() ? (float)$filiere->getCoutAnnuel() : 0;
        if ($cout > 1000000) {
            $risques[] = "Coût de formation élevé: " . number_format($cout, 0, ',', ' ') . " FCFA/an";
        }

        return $risques;
    }

    private function parseMatieresImportantes(?string $matieres): array
    {
        if (!$matieres) {
            return [];
        }

        return array_map('trim', explode(',', $matieres));
    }

    private function extraireMotsCles(string $texte): array
    {
        $motsCommuns = ['le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'et', 'en', 'pour', 'avec', 'dans'];

        $mots = preg_split('/[\s,;.]+/', strtolower($texte));
        $mots = array_filter($mots, fn($mot) => strlen($mot) > 3 && !in_array($mot, $motsCommuns));

        return array_unique($mots);
    }

    public function comparerEcoles(array $ecoles): array
    {
        $comparaison = [];

        foreach ($ecoles as $ecole) {
            $comparaison[] = [
                'ecole' => $ecole,
                'cout' => $ecole->getCoutScolarite() ? (float)$ecole->getCoutScolarite() : 0,
                'tauxInsertion' => $ecole->getTauxInsertion() ? (float)$ecole->getTauxInsertion() : 0,
                'nombreFilieres' => $ecole->getFilieres()->count(),
                'estVerifiee' => $ecole->isEstVerifiee(),
                'noteGlobale' => $ecole->getMoyenneAvis(),
                'type' => $ecole->getType()
            ];
        }

        return $comparaison;
    }
}
