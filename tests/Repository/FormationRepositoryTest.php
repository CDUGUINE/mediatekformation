<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author cdugu
 */
class FormationRepositoryTest extends KernelTestCase {
    public function recupRepository(): FormationRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }
    
    protected function setUp(): void {
        parent::setUp();
        self::bootKernel(); // Assure que le kernel est bien démarré
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $entityManager->getConnection()->beginTransaction(); // Démarre une transaction
    }

    public function testNbFormations(){
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }
    
    public function newFormation(): Formation {
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $playlistRepository = static::getContainer()->get(PlaylistRepository::class);

        $playlist = new Playlist();
        $playlist->setName("playlist de test");

        $entityManager->persist($playlist);  // Sauvegarde la playlist
        $entityManager->flush();  // Force l'enregistrement en base

        $formation = (new Formation())
            ->setPublishedAt(new \DateTime("now"))
            ->setTitle("ma formation")
            ->setVideoId("mapetiteidvideo")
            ->setPlaylist($playlist);
        return $formation;
    }
    
    public function testAddFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();

        // Récupération du nombre de formations avant l'ajout
        $nbFormationsAvant = $repository->count([]);

        // Ajout de la formation
        $repository->add($formation);

        // Rafraîchir le nombre de formations après l'ajout
        $nbFormationsApres = $repository->count([]);

        // Vérification que le nombre a bien augmenté de 1
        $this->assertEquals($nbFormationsAvant + 1, $nbFormationsApres, "Erreur lors de l'ajout de la formation");
    }

    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();

        // Ajout d'une formation
        $repository->add($formation);

        // Récupération du nombre de formations avant la supression
        $nbFormationsAvant = $repository->count([]);

        // Ajout de la formation
        $repository->remove($formation);

        // Rafraîchir le nombre de formations après la suppression
        $nbFormationsApres = $repository->count([]);

        // Vérification que le nombre a bien augmenté de 1
        $this->assertEquals($nbFormationsAvant - 1, $nbFormationsApres, "Erreur lors de la suppression de la formation");
    }
    
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation);
        $formations = $repository->findByContainValue("title", "ma format");
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEquals("ma formation", $formations[0]->getTitle());
    }
    
    public function testFindAllLasted(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation);
        $formations = $repository->findAllLasted(3);
        $nbFormations = count($formations);
        $this->assertEquals(3, $nbFormations);
    }
    
   public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation);
        $formations = $repository->findAllForOnePlaylist(8);
        $nbFormations = count($formations);
        // il y a 13 formations dans la playlist d'id 8
        $this->assertEquals(13, $nbFormations);
    }
    
}
