<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\Collection as Collection2;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author cdugu
 */
class PlaylistRepositoryTest extends KernelTestCase
{
    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    
    public function testNbPlaylists(){
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(28, $nbPlaylists);
    }

    public function newPlaylist(): Playlist {
        $playlist = (new Playlist())
            ->setName("ma playlist")
            ->setDescription("ma description");
        return $playlist;
    }

    public function testAddPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylistsAvant = $repository->count([]);
        $repository->add($playlist);
        $nbPlaylistsApres = $repository->count([]);
        $this->assertEquals($nbPlaylistsAvant + 1, $nbPlaylistsApres, "Erreur lors de l'ajout de la playlist");
    }
    
    public function testRemovePlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist);
        $nbPlaylistsAvant = $repository->count([]);
        $repository->remove($playlist);
        $nbPlaylistsApres = $repository->count([]);
        $this->assertEquals($nbPlaylistsAvant - 1, $nbPlaylistsApres, "Erreur lors de la suppression de la playlist");
    }
    
    public function testGetCategoriesPlaylist(){
        // Création des catégories
        $categorie1 = new Categorie();
        $categorie1->setName("Développement Web");

        $categorie2 = new Categorie();
        $categorie2->setName("Design");

        $categorie3 = new Categorie();
        $categorie3->setName("Développement Web"); // Même nom que $categorie1

        // Création des formations et assignation des catégories
        $formation1 = new Formation();
        $formation1->setTitle("Formation Symfony");
        $formation1->addCategory($categorie1);

        $formation2 = new Formation();
        $formation2->setTitle("Formation UX/UI");
        $formation2->addCategory($categorie2);

        $formation3 = new Formation();
        $formation3->setTitle("Formation Laravel");
        $formation3->addCategory($categorie3); // Même catégorie que $formation1

        // Création de la playlist
        $playlist = new Playlist();
        $playlist->setName("Playlist Développement");

        // Ajout des formations à la playlist
        $playlist->addFormation($formation1);
        $playlist->addFormation($formation2);
        $playlist->addFormation($formation3);

        // Exécution de la méthode testée
        $categoriesPlaylist = $playlist->getCategoriesPlaylist();

        // Vérifications
        $this->assertInstanceOf(Collection2::class, $categoriesPlaylist, "Le retour doit être une Collection.");
        $this->assertCount(2, $categoriesPlaylist, "Il ne doit y avoir que 2 catégories distinctes.");
        $this->assertContains("Développement Web", $categoriesPlaylist);
        $this->assertContains("Design", $categoriesPlaylist);

    }

}
