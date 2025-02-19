<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur du back office des playlits
 * @author cdugu
 */
class AdminPlaylistsController extends AbstractController{
   private $playlistRepository;
   
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * chemin des playlists
     */
    private const CHEMIN_ADMIN_PLAYLISTS = "admin/admin.playlists.html.twig";
    
    /**
     * Constructeur
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(PlaylistRepository $playlistRepository, CategorieRepository $categorieRepository)
    {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * Affiche toutes les playlits
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
    
    /**
     * Supprime une playlist
     * @param int $id
     * @return Response
     */
    #[Route('/admin/playlist/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr(int $id): Response{
        $playlist = $this->playlistRepository->find($id);
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }
    
    /**
     * Modifie une playlist
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response{
        $playlist = $this->playlistRepository->find($id);
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("admin/admin.playlist.edit.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    
    /**
     * Ajoute une playlist
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/ajout', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("admin/admin.playlist.ajout.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    
    /**
     * Trie les playlists sur le champ sélectionné
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response
    {
        switch ($champ) {
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "formations":
                $playlists = $this->playlistRepository->findAllOrderByFormations($ordre);
                break;
            default :
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Recherche les playlists contenant le texte saisi
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $playlist = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_ADMIN_PLAYLISTS, [
            'playlists' => $playlist,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
}

