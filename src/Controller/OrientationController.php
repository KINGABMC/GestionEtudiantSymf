<?php

namespace App\Controller;

use App\Repository\EcoleRepository;
use App\Repository\FiliereRepository;
use App\Repository\TypeBacRepository;
use App\Service\ConseillerOrientationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/orientation')]
final class OrientationController extends AbstractController
{
    private const LIMIT = 12;

    public function __construct(
        private readonly EcoleRepository $ecoleRepository,
        private readonly FiliereRepository $filiereRepository,
        private readonly TypeBacRepository $typeBacRepository,
        private readonly ConseillerOrientationService $conseillerService
    ) {
    }

    #[Route('/', name: 'app_orientation_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('orientation/index.html.twig', [
            'typesBac' => $this->typeBacRepository->findActifs(),
        ]);
    }

    #[Route('/ecoles', name: 'app_orientation_ecoles', methods: ['GET'])]
    public function listeEcoles(Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $offset = ($page - 1) * self::LIMIT;

        $filters = [
            'type' => $request->query->get('type'),
            'ville' => $request->query->get('ville'),
            'region' => $request->query->get('region'),
            'search' => $request->query->get('search'),
        ];

        $ecoles = $this->ecoleRepository->findByFilters($filters);

        $total = count($ecoles);
        $ecolesPaginated = array_slice($ecoles, $offset, self::LIMIT);
        $nbrPages = ceil($total / self::LIMIT);

        return $this->render('orientation/ecoles.html.twig', [
            'ecoles' => $ecolesPaginated,
            'currentPage' => $page,
            'nbrPages' => $nbrPages,
            'filters' => $filters,
        ]);
    }

    #[Route('/ecole/{id}', name: 'app_orientation_ecole_detail', methods: ['GET'])]
    public function detailEcole(int $id): Response
    {
        $ecole = $this->ecoleRepository->find($id);

        if (!$ecole) {
            throw $this->createNotFoundException('École introuvable');
        }

        return $this->render('orientation/ecole_detail.html.twig', [
            'ecole' => $ecole,
        ]);
    }

    #[Route('/filieres', name: 'app_orientation_filieres', methods: ['GET'])]
    public function listeFilieres(Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $offset = ($page - 1) * self::LIMIT;

        $typeBacId = $request->query->getInt('typeBac');
        $typeBac = $typeBacId ? $this->typeBacRepository->find($typeBacId) : null;

        $filieres = $typeBac
            ? $this->filiereRepository->findByTypeBac($typeBac)
            : $this->filiereRepository->findActives();

        $total = count($filieres);
        $filieresPaginated = array_slice($filieres, $offset, self::LIMIT);
        $nbrPages = ceil($total / self::LIMIT);

        return $this->render('orientation/filieres.html.twig', [
            'filieres' => $filieresPaginated,
            'typesBac' => $this->typeBacRepository->findActifs(),
            'selectedTypeBac' => $typeBac,
            'currentPage' => $page,
            'nbrPages' => $nbrPages,
        ]);
    }

    #[Route('/filiere/{id}', name: 'app_orientation_filiere_detail', methods: ['GET'])]
    public function detailFiliere(int $id): Response
    {
        $filiere = $this->filiereRepository->find($id);

        if (!$filiere) {
            throw $this->createNotFoundException('Filière introuvable');
        }

        return $this->render('orientation/filiere_detail.html.twig', [
            'filiere' => $filiere,
        ]);
    }

    #[Route('/conseiller', name: 'app_orientation_conseiller', methods: ['GET', 'POST'])]
    public function conseillerIntelligent(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $typeBacId = $request->request->getInt('typeBac');
            $moyenne = $request->request->get('moyenne');
            $centresInteret = $request->request->get('centresInteret', '');

            $typeBac = $this->typeBacRepository->find($typeBacId);

            if (!$typeBac || !$moyenne) {
                $this->addFlash('error', 'Veuillez renseigner tous les champs obligatoires.');
                return $this->redirectToRoute('app_orientation_conseiller');
            }

            $centresInteretArray = array_filter(array_map('trim', explode(',', $centresInteret)));

            $recommandations = $this->conseillerService->recommanderFilieres(
                $typeBac,
                (float) $moyenne,
                $centresInteretArray
            );

            return $this->render('orientation/resultats_conseiller.html.twig', [
                'recommandations' => $recommandations,
                'typeBac' => $typeBac,
                'moyenne' => $moyenne,
            ]);
        }

        return $this->render('orientation/conseiller.html.twig', [
            'typesBac' => $this->typeBacRepository->findActifs(),
        ]);
    }

    #[Route('/comparateur', name: 'app_orientation_comparateur', methods: ['GET'])]
    public function comparateur(Request $request): Response
    {
        $ecoleIds = $request->query->all('ecoles');

        if (empty($ecoleIds)) {
            return $this->render('orientation/comparateur.html.twig', [
                'ecoles' => $this->ecoleRepository->findActives(),
                'comparaison' => null,
            ]);
        }

        $ecolesAComparer = [];
        foreach ($ecoleIds as $id) {
            $ecole = $this->ecoleRepository->find($id);
            if ($ecole) {
                $ecolesAComparer[] = $ecole;
            }
        }

        $comparaison = $this->conseillerService->comparerEcoles($ecolesAComparer);

        return $this->render('orientation/comparateur.html.twig', [
            'ecoles' => $this->ecoleRepository->findActives(),
            'comparaison' => $comparaison,
        ]);
    }
}
