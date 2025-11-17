<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class acceuilController extends AbstractController
{
    #[Route('/acceuil', name: 'app_home',methods:['GET','POST'])]
    public function index(): Response
    {
        return $this->render('acceuil/acceuil.html.twig', [
            
        ]);
    }

}
