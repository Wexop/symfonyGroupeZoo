<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Form\EspaceSupprimerType;
use App\Form\EspaceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

class EspacesController extends AbstractController
{
    #[Route('/', name: 'app_espaces')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

        $espace = new Espace();
        $form = $this->createForm(EspaceType::class, $espace);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ((($form->getData()->getDateOuverture() !== null && $form->getData()->getDateFermeture() !== null) && $form->getData()->getDateOuverture() < $form->getData()->getDateFermeture())
                || ($form->getData()->getDateOuverture() === null && $form->getData()->getDateFermeture() === null)
            ) {
                $em = $doctrine->getManager();

                $em->persist($espace);

                $em->flush();
            } else {
                throw $this->createNotFoundException("Erreur dans le formulaire : vérifier si les dates d'ouverture et de fermeture sont correctement indiquées");
            }
        }

        $repo = $doctrine->getRepository(Espace::class);
        $espace = $repo->findAll();

        return $this->render('espaces/index.html.twig', [
            "espaces" => $espace,
            "formulaire" => $form->createView()
        ]);
    }

    #[Route('/espaces/supprimer/{id}', name: 'espaces_supprimer')]
    public function supprimerEspace($id, ManagerRegistry $doctrine, Request $request)
    {

        $espace = $doctrine->getRepository(Espace::class)->find($id);


        if (!$espace) {
            throw $this->createNotFoundException("Aucun espace avec l'id $id");
        }

        $form = $this->createForm(EspaceSupprimerType::class, $espace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();

            $em->remove($espace);

            $em->flush();

            return $this->redirectToRoute("app_espaces");

        }

        return $this->render("espaces/supprimer.html.twig", [
            "espace" => $espace,
            "formulaire" => $form->createView()
        ]);


    }

    #[Route('/espaces/modifier/{id}', name: 'espaces_modifier')]
    public function modifierCategorie($id, ManagerRegistry $doctrine, Request $request)
    {
        $espace = $doctrine->getRepository(Espace::class)->find($id);

        if (!$espace) {
            throw $this->createNotFoundException("Aucune espace avec l'id $id");
        }

        $form = $this->createForm(EspaceType::class, $espace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ((($form->getData()->getDateOuverture() !== null && $form->getData()->getDateFermeture() !== null) && $form->getData()->getDateOuverture() < $form->getData()->getDateFermeture())
                || ($form->getData()->getDateOuverture() === null && $form->getData()->getDateFermeture() === null)
            ) {


                $em = $doctrine->getManager();

                $em->persist($espace);

                $em->flush();

                return $this->redirectToRoute("app_espaces");

            } else {
                throw $this->createNotFoundException("Erreur dans le formulaire : vérifier si les dates d'ouverture et de fermeture sont correctement indiquées");
            }
        }


            return $this->render("espaces/modifier.html.twig", [
                "espace" => $espace,
                "formulaire" => $form->createView()
            ]);


        }
}
