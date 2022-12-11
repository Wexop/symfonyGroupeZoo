<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Enclos;
use App\Form\AnimalType;
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

    #[Route('/animaux/ajouter/', name: 'animaux_ajouter')]
    public function ajouterAnimaux(ManagerRegistry $doctrine, Request $request)
    {
        $animal = new Animal();

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();

            $em->persist($animal);

            $em->flush();

            return $this->redirectToRoute("enclos_voirAnimaux", ["id" => $animal->getEnclos()->getId()]);

        }


        return $this->render("animaux/index.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }
}
