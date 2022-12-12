<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Enclos;
use App\Entity\Espace;
use App\Form\EnclosSupprimerType;
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

    #[Route('/enclos/ajouter/', name: 'enclos_ajouter')]
    public function ajouterEnclos(ManagerRegistry $doctrine, Request $request)
    {
        $enclos = new Enclos();

        $form = $this->createForm(EnclosType::class, $enclos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $animaux = $enclos->getAnimaux();

            $newEnclosSuperficie = $form->getData()->getSuperficie();
            $espaceEnclos = $form->getData()->getEspaceId()->getEnclos();
            $superficieLibre = $form->getData()->getEspaceId()->getSuperficie();

            foreach ($espaceEnclos as $enclo) {
                $superficieLibre -= $enclo->getSuperficie();
            }

            if ($superficieLibre < $newEnclosSuperficie) {
                throw $this->createNotFoundException("Cet enclos prend trop de place dans cet espace");
            }

            if ($enclos->isQuarentaine()) {
                foreach ($animaux as $a) {
                    $a->setQuarentaine(true);
                }
            }

            $em = $doctrine->getManager();

            $em->persist($enclos);

            $em->flush();

            return $this->redirectToRoute("voir_enclos", ["id" => $enclos->getEspaceId()->getId()]);

        }


        return $this->render("enclos/index.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }

    #[Route('/espaces/supprimer/{id}', name: 'enclos_supprimer')]
    public function supprimerEnclos($id, ManagerRegistry $doctrine, Request $request)
    {

        $enclos = $doctrine->getRepository(Enclos::class)->find($id);


        if (!$enclos) {
            throw $this->createNotFoundException("Aucun enclos avec l'id $id");
        }

        $form = $this->createForm(EnclosSupprimerType::class, $enclos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($enclos->getAnimaux()->isEmpty()) {

                $em = $doctrine->getManager();

                $em->remove($enclos);

                $em->flush();

                return $this->redirectToRoute("voir_enclos", ["id" => $enclos->getEspaceID()->getId()]);
            } else throw $this->createNotFoundException("Impossible de supprimer un enclos qui contient des animaux !");

        }

        return $this->render("enclos/supprimer.html.twig", [
            "enclos" => $enclos,
            "formulaire" => $form->createView()
        ]);
    }

    #[Route('/enclos/modifier/{id}', name: 'enclos_modifier')]
    public function modifierEnclos($id, ManagerRegistry $doctrine, Request $request)
    {
        $enclos = $doctrine->getRepository(Enclos::class)->find($id);

        if (!$enclos) {
            throw $this->createNotFoundException("Aucun enclos avec l'id $id");
        }

        $form = $this->createForm(EnclosType::class, $enclos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $animaux = $enclos->getAnimaux();

            $newEnclosSuperficie = $form->getData()->getSuperficie();
            $espaceEnclos = $form->getData()->getEspaceId()->getEnclos();
            $superficieLibre = $form->getData()->getEspaceId()->getSuperficie();

            foreach ($espaceEnclos as $enclo) {
                $superficieLibre -= $enclo->getSuperficie();
            }

            if ($superficieLibre < $newEnclosSuperficie) {
                throw $this->createNotFoundException("Cet enclos prend trop de place dans cet espace");
            }

            if ($enclos->isQuarentaine()) {
                foreach ($animaux as $a) {
                    $a->setQuarentaine(true);
                }
            }

            $em = $doctrine->getManager();

            $em->persist($enclos);

            $em->flush();

            return $this->redirectToRoute("voir_enclos", ["id" => $enclos->getEspaceId()->getId()]);

        }


        return $this->render("enclos/index.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }

}
