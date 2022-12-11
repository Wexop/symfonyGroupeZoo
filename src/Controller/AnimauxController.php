<?php

namespace App\Controller;

use App\Entity\Enclos;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimauxController extends AbstractController
{
    #[Route('/enclos/voirAnimaux/{id}', name: 'enclos_voirAnimaux')]
    public function index($id, ManagerRegistry $doctrine, Request $request)
    {

        $enclos = $doctrine->getRepository(Enclos::class)->find($id);
        $animaux = [];


        if (!$enclos) {
            throw $this->createNotFoundException("Aucun enclos avec l'id $id");
        }


        return $this->render("enclos/voirAnimaux.html.twig", [
            "animaux" => $animaux,
            "enclos" => $enclos
        ]);
    }
}
