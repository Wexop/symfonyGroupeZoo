<?php

namespace App\Controller;

use App\Entity\Enclos;
use App\Entity\Espace;
use App\Form\EnclosType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnclosController extends AbstractController
{
    #[Route('/espaces/voirEnclos/{id}', name: 'voir_enclos')]
    public function index($id, ManagerRegistry $doctrine, Request $request)
    {

        $espace = $doctrine->getRepository(Espace::class)->find($id);
        $enclos = $espace->getEnclos();


        if (!$espace) {
            throw $this->createNotFoundException("Aucun espace avec l'id $id");
        }


        return $this->render("espaces/voirEnclos.html.twig", [
            "espace" => $espace,
            "enclos" => $enclos
        ]);


    }

}
