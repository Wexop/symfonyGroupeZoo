<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Enclos;
use App\Form\AnimalSupprimerType;
use App\Form\AnimalType;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;
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
        $animaux = $enclos->getAnimaux();


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
            $tailleNID = strlen(sprintf('%014d',$animal->getNumeroIdentification()));
            if ($tailleNID == 14) {

                if ($animal->getDateNaissance() == null ||
                    $animal->getDateNaissance() < $animal->getDateArrive()
                ) {
                    if ($animal->getDateDepart() == null || $animal->getDateArrive() < $animal->getDateDepart()) {

                        if ($animal->isSterile() == 1 && $animal->getGenre() != "non déterminé" || $animal->isSterile() == 0) {

                            $em = $doctrine->getManager();
                            $em->persist($animal);
                            $em->flush();

                            return $this->redirectToRoute("enclos_voirAnimaux", ["id" => $animal->getEnclos()->getId()]);

                        } else throw $this->createNotFoundException("L'animal doit avoir un genre pour être stérile");

                    } else  throw $this->createNotFoundException("La date d'arrivée doit être inférieur à la date de départ !");

                } else throw $this->createNotFoundException("La date de naissance ne peut pas être inférieur à la date d'arrivée au zoo !");

            } else throw $this->createNotFoundException("Le numero d'identification doit faire 14 chiffres, celui ci fait $tailleNID");

        }


        return $this->render("animaux/index.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }

    #[Route('/animaux/modifier/{id}', name: 'animaux_modifier')]
    public function modifierAnimaux($id, ManagerRegistry $doctrine, Request $request)
    {
        $animal = $doctrine->getRepository(Animal::class)->find($id);

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tailleNID = strlen((string)$animal->getNumeroIdentification());
            if ($tailleNID == 14) {

                if ($animal->getDateNaissance() == null ||
                    $animal->getDateNaissance() < $animal->getDateArrive()
                ) {
                    if ($animal->getDateDepart() == null || $animal->getDateArrive() < $animal->getDateDepart()) {

                        if ($animal->isSterile() == 1 && $animal->getGenre() != "non déterminé" || $animal->isSterile() == 0) {

                            $em = $doctrine->getManager();
                            $em->persist($animal);
                            $em->flush();

                            return $this->redirectToRoute("enclos_voirAnimaux", ["id" => $animal->getEnclos()->getId()]);

                        } else throw $this->createNotFoundException("L'animal doit avoir un genre pour être stérile");

                    } else  throw $this->createNotFoundException("La date d'arrivée doit être inférieur à la date de départ !");

                } else throw $this->createNotFoundException("La date de naissance ne peut pas être inférieur à la date d'arrivée au zoo !");

            } else throw $this->createNotFoundException("Le numero d'identification doit faire 14 chiffres, celui ci fait $tailleNID");

        }


        return $this->render("animaux/index.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }

    #[Route('/animaux/supprimer/{id}', name: 'animaux_supprimer')]
    public function supprimerAnimaux($id, ManagerRegistry $doctrine, Request $request)
    {
        $animal = $doctrine->getRepository(Animal::class)->find($id);

        $form = $this->createForm(AnimalSupprimerType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $em->remove($animal);
            $em->flush();

            return $this->redirectToRoute("enclos_voirAnimaux", ["id" => $animal->getEnclos()->getId()]);


        }


        return $this->render("animaux/supprimer.html.twig", [
            "formulaire" => $form->createView(),
            "animal" => $animal
        ]);
    }
}
