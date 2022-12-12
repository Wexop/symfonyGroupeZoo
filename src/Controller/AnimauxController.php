<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Enclos;
use App\Form\AnimalSupprimerType;
use App\Form\AnimalType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            $enclosId = $animal->getEnclos()->getId();
            $enclos = $doctrine->getRepository(Enclos::class)->find($enclosId);
            $enclosMaxAnimaux = $enclos->getMaxAnimaux();

            $animaux = $doctrine->getRepository(Animal::class)->findAll();
            $nbAnimauxEnclos = 0;

            foreach ($animaux as $a) {
                if ($a->getEnclos()->getId() == $enclosId) $nbAnimauxEnclos += 1;
                if ($a->getNumeroIdentification() == $animal->getNumeroIdentification()) throw  $this->createNotFoundException("ce numero d'identification appartient à un autre animal !");
            }

            $tailleNID = strlen($animal->getNumeroIdentification());

            if ($tailleNID == 14 && is_numeric( $animal->getNumeroIdentification())) {

                if ($animal->getDateNaissance() == null ||
                    $animal->getDateNaissance() < $animal->getDateArrive()
                ) {
                    if ($animal->getDateDepart() == null || $animal->getDateArrive() < $animal->getDateDepart()) {

                        if ($animal->isSterile() == 1 && $animal->getGenre() != "non déterminé" || $animal->isSterile() == 0) {

                            if ($nbAnimauxEnclos < $enclosMaxAnimaux) {

                                if ($enclos->isQuarentaine()) {
                                    $everyAnimauxNotQuarentaine = true;
                                    foreach ($enclos->getAnimaux() as $a) {
                                        $a->isQuarentaine() && $everyAnimauxNotQuarentaine = false;
                                    }

                                    if ($everyAnimauxNotQuarentaine) {
                                        $enclos->setQuarentaine(false);
                                    }
                                } else {
                                    if ($animal->isQuarentaine()) $enclos->setQuarentaine(true);
                                }

                                $em = $doctrine->getManager();
                                $em->persist($animal);
                                $em->flush();

                                return $this->redirectToRoute("enclos_voirAnimaux", ["id" => $animal->getEnclos()->getId()]);

                            } else throw $this->createNotFoundException("Il y a trop d'animaux dans cet enclos !");

                        } else throw $this->createNotFoundException("L'animal doit avoir un genre pour être stérile");

                    } else  throw $this->createNotFoundException("La date d'arrivée doit être inférieur à la date de départ !");

                } else throw $this->createNotFoundException("La date de naissance ne peut pas être inférieur à la date d'arrivée au zoo !");

            } else $tailleNID != 14 ? throw $this->createNotFoundException("Le numero d'identification doit faire 14 chiffres, celui ci fait $tailleNID")
            : throw $this->createNotFoundException("Le numero d'identification doit contenir seulement des chiffres !");

        }


        return $this->render("animaux/index.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }

    #[Route('/animaux/modifier/{id}', name: 'animaux_modifier')]
    public function modifierAnimaux($id, ManagerRegistry $doctrine, Request $request)
    {
        $animal = $doctrine->getRepository(Animal::class)->find($id);

        if (!$animal) throw $this->createNotFoundException("aucun animal avec l'id $id");

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enclosId = $animal->getEnclos()->getId();
            $enclos = $doctrine->getRepository(Enclos::class)->find($enclosId);
            $enclosMaxAnimaux = $enclos->getMaxAnimaux();

            $animaux = $doctrine->getRepository(Animal::class)->findAll();
            $nbAnimauxEnclos = 0;

            foreach ($animaux as $a) {
                if ($a->getEnclos()->getId() == $enclosId) $nbAnimauxEnclos += 1;
                if ($a->getNumeroIdentification() == $animal->getNumeroIdentification() && $a->getId() != $animal->getId()) throw  $this->createNotFoundException("ce numero d'identification appartient à un autre animal !");

            }

            $tailleNID = strlen($animal->getNumeroIdentification());

            if ($tailleNID == 14) {

                if ($animal->getDateNaissance() == null ||
                    $animal->getDateNaissance() < $animal->getDateArrive()
                ) {
                    if ($animal->getDateDepart() == null || $animal->getDateArrive() < $animal->getDateDepart()) {

                        if ($animal->isSterile() == 1 && $animal->getGenre() != "non déterminé" || $animal->isSterile() == 0) {

                            if ($nbAnimauxEnclos <= $enclosMaxAnimaux) {

                                if ($enclos->isQuarentaine()) {
                                    $everyAnimauxNotQuarentaine = true;
                                    foreach ($enclos->getAnimaux() as $a) {
                                        $a->isQuarentaine() && $everyAnimauxNotQuarentaine = false;
                                    }

                                    if ($everyAnimauxNotQuarentaine) {
                                        $enclos->setQuarentaine(false);
                                    }
                                } else {
                                    if ($animal->isQuarentaine()) $enclos->setQuarentaine(true);
                                }

                                $em = $doctrine->getManager();
                                $em->persist($animal);
                                $em->flush();

                                return $this->redirectToRoute("enclos_voirAnimaux", ["id" => $animal->getEnclos()->getId()]);

                            } else throw $this->createNotFoundException("Il y a trop d'animaux dans cet enclos !");

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

        if (!$animal) throw $this->createNotFoundException("aucun animal avec l'id $id");

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
