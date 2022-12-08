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

            if ( (($form->getData()->getDateOuverture() !== null && $form->getData()->getDateFermeture() !== null) && $form->getData()->getDateOuverture() < $form->getData()->getDateFermeture())
                || ($form->getData()->getDateOuverture() === null && $form->getData()->getDateFermeture() === null )
            ) {
                $em = $doctrine->getManager();

                $em->persist($espace);

                $em->flush();
            }
            else {
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
        //Récupérer la catégorie dans la BDD
        $espace = $doctrine->getRepository(Espace::class)->find($id);

        //si on n'a rien trouvé -> 404

        if (!$espace) {
            throw $this->createNotFoundException("Aucun espace avec l'id $id");
        }

        //si on arrive la, c'est qu'on a trouvé une catégorie
        //on crée le formulaire avec (il sera rempli avec ses valeurs)
        $form = $this->createForm(EspaceSupprimerType::class, $espace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // le handleRequest a rempli notre objet $vcategorieNew
            // qui n'est plus vide
            //pour sauvegarder, on va récupérer un entitymanager de doctrine
            //qui comme son nom l'indique gère les identités
            $em = $doctrine->getManager();
            // on lui dit de la supprimer de la BDD
            $em->remove($espace);

            //générer l'insert
            $em->flush();

            //retour à la page d'accueil
            return $this->redirectToRoute("app_espaces");

        }


        return $this->render("espaces/supprimer.html.twig", [
            "espace" => $espace,
            "formulaire" => $form->createView()
        ]);


    }

}
