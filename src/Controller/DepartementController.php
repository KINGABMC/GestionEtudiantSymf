<?php

namespace App\Controller;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request as Rrequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class DepartementController extends AbstractController
{
    private const LIMIT = 4;
    public function __construct(private readonly DepartementRepository $departementRepository,
    private readonly EntityManagerInterface $Manager)
    {
    }
    /*
    Liste des départements ==> GET
    Creer un département ==> POST(name  )
    */
  #[Route('/departement/list', name: 'app_departement_list', methods:['GET','POST'])]
public function list(Rrequest $request): Response
{  
    $departement = new \App\Entity\Departement();
    $form = $this->createForm(\App\Form\DepartementType::class, $departement);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $this->Manager->persist($departement);
        $this->Manager->flush();
        
        // Message de succès
        $this->addFlash('success', 'Département créé avec succès!');
        return $this->redirectToRoute('app_departement_list');
    }
    
    $page = $request->query->get("page", 1);
    $offset = ($page - 1) * self::LIMIT;
    $departements = $this->departementRepository->findBy(
        [], 
        ['id' => 'DESC'], 
        self::LIMIT,
        $offset
    );
    $departementsDto = \App\DTO\DepartementListDto::fromEntities($departements);
    $count = $this->departementRepository->count([]);
    $nbrPages = ceil($count / self::LIMIT);
    
    return $this->render('departement/liste.html.twig', [
        'departements' => $departementsDto,
        'currentPage' => $page,
        'nbrPages' => $nbrPages,
        'formDept' => $form->createView()
    ]);
}

}