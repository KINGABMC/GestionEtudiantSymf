<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Ecole;
use App\Entity\Filiere;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\EcoleRepository;
use App\Repository\FiliereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/avis')]
final class AvisController extends AbstractController
{
    public function __construct(
        private readonly AvisRepository $avisRepository,
        private readonly EcoleRepository $ecoleRepository,
        private readonly FiliereRepository $filiereRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/ecole/{id}/ajouter', name: 'app_avis_ecole_add', methods: ['GET', 'POST'])]
    public function ajouterAvisEcole(int $id, Request $request): Response
    {
        $ecole = $this->ecoleRepository->find($id);

        if (!$ecole) {
            throw $this->createNotFoundException('École introuvable');
        }

        $avis = new Avis();
        $avis->setEcole($ecole);
        $avis->setUser($this->getUser());

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setEstPublie(true);
            $avis->setEstVerifie(false);

            $this->entityManager->persist($avis);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre avis a été soumis et sera examiné avant publication.');

            return $this->redirectToRoute('app_orientation_ecole_detail', ['id' => $ecole->getId()]);
        }

        return $this->render('avis/form.html.twig', [
            'form' => $form,
            'ecole' => $ecole,
            'filiere' => null,
            'titre' => 'Ajouter un avis pour ' . $ecole->getNom(),
        ]);
    }

    #[Route('/filiere/{id}/ajouter', name: 'app_avis_filiere_add', methods: ['GET', 'POST'])]
    public function ajouterAvisFiliere(int $id, Request $request): Response
    {
        $filiere = $this->filiereRepository->find($id);

        if (!$filiere) {
            throw $this->createNotFoundException('Filière introuvable');
        }

        $avis = new Avis();
        $avis->setFiliere($filiere);
        $avis->setUser($this->getUser());

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setEstPublie(true);
            $avis->setEstVerifie(false);

            $this->entityManager->persist($avis);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre avis a été soumis et sera examiné avant publication.');

            return $this->redirectToRoute('app_orientation_filiere_detail', ['id' => $filiere->getId()]);
        }

        return $this->render('avis/form.html.twig', [
            'form' => $form,
            'ecole' => null,
            'filiere' => $filiere,
            'titre' => 'Ajouter un avis pour ' . $filiere->getNom(),
        ]);
    }

    #[Route('/ecole/{id}', name: 'app_avis_ecole_liste', methods: ['GET'])]
    public function listeAvisEcole(int $id, Request $request): Response
    {
        $ecole = $this->ecoleRepository->find($id);

        if (!$ecole) {
            throw $this->createNotFoundException('École introuvable');
        }

        $page = max(1, $request->query->getInt('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $avis = $this->avisRepository->findPubliesForEcole($ecole);

        $total = count($avis);
        $avisPaginated = array_slice($avis, $offset, $limit);
        $nbrPages = ceil($total / $limit);

        return $this->render('avis/liste_ecole.html.twig', [
            'ecole' => $ecole,
            'avis' => $avisPaginated,
            'currentPage' => $page,
            'nbrPages' => $nbrPages,
            'total' => $total,
        ]);
    }

    #[Route('/filiere/{id}', name: 'app_avis_filiere_liste', methods: ['GET'])]
    public function listeAvisFiliere(int $id, Request $request): Response
    {
        $filiere = $this->filiereRepository->find($id);

        if (!$filiere) {
            throw $this->createNotFoundException('Filière introuvable');
        }

        $page = max(1, $request->query->getInt('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $avis = $this->avisRepository->findPubliesForFiliere($filiere);

        $total = count($avis);
        $avisPaginated = array_slice($avis, $offset, $limit);
        $nbrPages = ceil($total / $limit);

        return $this->render('avis/liste_filiere.html.twig', [
            'filiere' => $filiere,
            'avis' => $avisPaginated,
            'currentPage' => $page,
            'nbrPages' => $nbrPages,
            'total' => $total,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'app_avis_delete', methods: ['POST'])]
    public function supprimerAvis(int $id, Request $request): Response
    {
        $avis = $this->avisRepository->find($id);

        if (!$avis) {
            throw $this->createNotFoundException('Avis introuvable');
        }

        if ($avis->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer cet avis');
        }

        if ($this->isCsrfTokenValid('delete' . $avis->getId(), $request->request->get('_token'))) {
            $ecoleId = $avis->getEcole()?->getId();
            $filiereId = $avis->getFiliere()?->getId();

            $this->entityManager->remove($avis);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre avis a été supprimé.');

            if ($ecoleId) {
                return $this->redirectToRoute('app_orientation_ecole_detail', ['id' => $ecoleId]);
            } elseif ($filiereId) {
                return $this->redirectToRoute('app_orientation_filiere_detail', ['id' => $filiereId]);
            }
        }

        return $this->redirectToRoute('app_orientation_home');
    }
}
