<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{

    private $repoProduit;
    private $manager;
    private $requestStack;
    private $request;
    

    public function __construct(ProduitRepository $repoProduit, EntityManagerInterface $manager, RequestStack $requestStack)
    {
        $this->repoProduit = $repoProduit;
        $this->manager = $manager;
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();
    }


    /**
     * @Route("/", name="accueil")
     */
    public function accueil()
    {

        return $this->render('accueil/accueil.html.twig', [
        ]);
    }

    /**
     * @Route("profil", name="profil")
     */
    public function profil()
    {
        return $this->render('accueil/profil.html.twig', []);
    }
}
