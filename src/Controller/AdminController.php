<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $repoProduit;
    private $repoAccueil;
    private $manager;
    private $requestStack;
    private $request;


    public function __construct(
        ProduitRepository $repoProduit,
        EntityManagerInterface $manager,
        RequestStack $requestStack
    ) {
        $this->repoProduit = $repoProduit;
        // $this->repoAccueil = $repoAccueil;
        $this->manager = $manager;
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();
    }


    /**
     * @Route("/dashboard", name="admin")
     */
    public function back_office()
    {


        return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/admin/produit_afficher", name="admin_produit_afficher")
     */
    public function admin_produit_afficher()
    {
        $produit = $this->repoProduit->findAll();
        return $this->render('admin/admin_produit_afficher.html.twig', [
            "produits" => $produit
        ]);
    }

    /**
     * @Route("/admin/produit_ajouter", name="produit_ajouter")
     */
    public function admin_produit_ajouter()
    {
        $produit = new Produit;

        
        $form = $this->createForm(ProduitType::class, $produit);
        
        
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($produit);
            $this->manager->flush();

            $this->addFlash(
               'success',
               'Votre produit N°'. $produit->getId() . ' a bien été ajouté.'
            );
            return $this->redirectToRoute('admin_produit_afficher');
        }


        return $this->render('admin/admin_produit_ajouter.html.twig', [
            'formProduit' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/produit_modifier/{id}", name="produit_modifier")
     */
    public function admin_produit_modifier(Produit $produit)
    {   
        $form = $this->createForm(ProduitType::class, $produit);
        
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $this->manager->persist($produit);
            $this->manager->flush();

            $this->addFlash(
               'success',
               'Votre produit N°'. $produit->getId() . ' a bien été modifié.'
            );
            return $this->redirectToRoute('admin_produit_afficher');
        }
        return $this->render('admin/admin_produit_modifier.html.twig', [
            'produits' => $produit,
            'formProduit' => $form->createView(),
        ]);
    }

        /**
     * @Route("/admin/produit_supprimer/{id}", name="produit_supprimer")
     */
    public function admin_produit_supprimer(Produit $produit)
    {   
            $idProduit = $produit->getId();

            $this->manager->remove($produit);
            $this->manager->flush();

            $this->addFlash(
               'warning',
               'Votre produit N°'. $idProduit . ' a bien été supprimé.'
            );
            return $this->redirectToRoute('admin_produit_afficher');
        return $this->render('admin/admin_produit_supprimer.html.twig');
    }
}
