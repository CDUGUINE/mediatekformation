<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur du front office des playlists
 * @author emds
 */
class PlaylistsController extends AbstractController
{
    
    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * chemin des playlists
     */
    private const CHEMIN_PLAYLISTS = "pages/playlists.html.twig";
    
    /**
     * Constructeur
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository)
    {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * Affiche toutes les playlists
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Trie les playlists sur le champ sélectionné
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
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
        return $this->render(self::CHEMIN_PLAYLISTS, [
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
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche la playlist sélectionnée
     * @param type $id
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);
    }

}
