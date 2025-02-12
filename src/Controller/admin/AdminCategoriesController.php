<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategoriesController
 *
 * @author cdugu
 */
class AdminCategoriesController extends AbstractController{
   
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * chemin des playlists
     */
    private const CHEMIN_ADMIN_CATEGORIES = "admin/admin.categories.html.twig";
    
    function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository= $categorieRepository;
    }
    
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_ADMIN_CATEGORIES, [
            'categories' => $categories
        ]);
    }
    
    #[Route('/admin/categorie/suppr/{id}', name: 'admin.categorie.suppr')]
    public function suppr(int $id): Response{
        $categorie = $this->categorieRepository->find($id);
        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }
    
    #[Route('/admin/categorie/ajout', name: 'admin.categorie.ajout')]
    public function ajout(Request $request): Response{
        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute('admin.categories');
        }
        
        return $this->render("admin/admin.categorie.ajout.html.twig", [
            'categorie' => $categorie,
            'formcategorie' => $formCategorie->createView()
        ]);
    }

}

