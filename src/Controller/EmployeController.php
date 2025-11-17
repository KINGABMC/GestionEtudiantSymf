<?php

namespace App\Controller;
use App\Entity\Departement;
use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Const_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
 use Symfony\Component\HttpFoundation\Request;
use App\Form\EmployeType;
use App\Service\impl\GenerateNumeroService;
use  Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\impl\FileUploaderService;
use Symfony\Component\Form\FormError;

final class EmployeController extends AbstractController
{
      private const LIMIT_ALL = 20;
      private const LIMIT = 4;
    public function __construct(private readonly \App\Repository\EmployeRepository $employeRepository,
                                private readonly \App\Repository\DepartementRepository $departementRepository,
                                private readonly EntityManagerInterface $Manager)
    {
    }
    /*
    Liste des employés ==> GET
    Creer un employé ==> POST(name, telephone, adresse, departement)
    */


#[Route('/employe/list', name: 'app_employe_list', methods:['GET'])]
public function list(Request $request): Response
{
    $page = $request->query->get("page", 1);
    $departement = null;
    
    // Récupère les filtres
    $idDept = $request->query->get('idDept');
    $statut = $request->query->get('statut'); // 'actif', 'archive', ou null
    $searchId = $request->query->get('searchId');
    // Construire les critères de recherche
    $criteria = [];

    // Filtre par ID
    if ($searchId) {
        $criteria['id'] = $searchId;
    }
    // Filtre par statut
    if ($statut === 'archive') {
        $criteria['isArchived'] = true;
    } elseif ($statut === 'actif') {
        $criteria['isArchived'] = false;
    }
    // Si $statut est null, on ne filtre pas par statut
    
    // Filtre par département
    if ($idDept) {
        $departement = $this->departementRepository->find($idDept);
        if ($departement) {
            $criteria['departement'] = $departement;
        } else {
            $employes = [];
            $nbrPages = 1;
            $count = 0;
        }
    }
    
    // Calcul de l'offset et récupération des employés
    $limit = $searchId ? 1 : ($idDept ? self::LIMIT : self::LIMIT_ALL);
    $offset = ($page - 1) * $limit;
    
    if (!isset($employes)) { // Si pas d'erreur de département
        $employes = $this->employeRepository->findBy($criteria, null, $limit, $offset);
        $count = $this->employeRepository->count($criteria);
        $nbrPages = ceil($count / $limit);
    }

    $departements = $this->departementRepository->findAll();
    
    // Paramètres supplémentaires pour la pagination
    $extraParams = [];
    if ($idDept) {
        $extraParams['idDept'] = $idDept;
    }
    if ($statut) {
        $extraParams['statut'] = $statut;
    }
    if ($searchId) {
        $extraParams['searchId'] = $searchId;
    }
     
    return $this->render('employe/liste.html.twig', [
        'employes' => $employes,
        'departements' => $departements,
        'departement' => $departement,
        'selectedDeptId' => $idDept,
        'selectedStatut' => $statut, // ← Nouveau: pour garder la sélection
        'currentPage' => $page,
        'nbrPages' => $nbrPages,
        'extraParams' => $extraParams
    ]);
}
/*
Creer un employé ==> POST(name, telephone, adresse, departement)
*/


#[Route('/employes/departement/{id}', name: 'app_employe_by_departement')]
public function byDepartement(Request $request, int $id): Response
{

    $page = $request->query->get("page", 1);
    $offset = ($page - 1) * self::LIMIT;
    
    // Récupération du département
    $departement = $this->departementRepository->find($id);

    if (!$departement) {
        throw $this->createNotFoundException('Département introuvable');
    }

    // Récupération du filtre statut
    $statut = $request->query->get('statut');
    
    // Construire les critères
    $criteria = ['departement' => $departement];
    
    // Filtre par statut
    if ($statut === 'archive') {
        $criteria['isArchived'] = true;
    } elseif ($statut === 'actif') {
        $criteria['isArchived'] = false;
    }

    // Récupération des employés liés à ce département avec pagination
    $employes = $this->employeRepository->findBy(
        $criteria, 
        null, 
        self::LIMIT, 
        $offset
    );
    
    // Calcul du nombre total d'employés et de pages
    $count = $this->employeRepository->count($criteria);
    $nbrPages = ceil($count / self::LIMIT);
    
    $departements = $this->departementRepository->findAll();
    
    $extraParams = ['idDept' => $id];
    if ($statut) {
        $extraParams['statut'] = $statut;
    }
    
    return $this->render('employe/liste.html.twig', [
        'employes' => $employes,
        'departement' => $departement,
        'departements' => $departements,
        'selectedDeptId' => $id,
        'selectedStatut' => $statut, // ← AJOUTER CETTE LIGNE
        'currentPage' => $page,
        'nbrPages' => $nbrPages,
        'extraParams' => $extraParams
    ]);
}

#[Route('/employe/add', name: 'app_employe_add', methods:['GET','POST'])]
public function save(Request $request, FileUploaderService $fileUploader): Response
{
    $employe = new Employe();
    $form = $this->createForm(EmployeType::class, $employe, [
        'csrf_protection' => false
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if ($form->isValid()) {
            $photoFile = $form->get('photoFile')->getData();

            if ($photoFile) {
                try {
                    $newFilename = $fileUploader->uploadFile($photoFile);
                    $employe->setPhoto($newFilename);
                } catch (\Exception $e) {
                    $form->get('photoFile')->addError(
                        new \Symfony\Component\Form\FormError(
                            'Erreur lors de l\'upload de la photo : ' . $e->getMessage()
                        )
                    );

                    return $this->render('employe/forme.html.twig', [
                        'formEmpt' => $form->createView(),
                    ]);
                }
            }

            $this->Manager->persist($employe);
            $this->Manager->flush();

            $this->addFlash('success', 'Employé ajouté avec succès!');
            return $this->redirectToRoute('app_employe_list');
        }
    }

    return $this->render('employe/forme.html.twig', [
        'formEmpt' => $form->createView(),
    ]);
}


}