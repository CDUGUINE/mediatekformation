<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 *
 * @author cdugu
 */
class CategorieRepositoryTest extends KernelTestCase
{
public function recupRepository(): CategorieRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }
    
    public function testNbCategories(){
        $repository = $this->recupRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(10, $nbCategories);
    }

    public function newCategorie(): Categorie {
        $categorie = (new Categorie())
            ->setName("ma catégorie");
        return $categorie;
    }
    
        public function testAddCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategoriesAvant = $repository->count([]);
        $repository->add($categorie);
        $nbCategoriesApres = $repository->count([]);
        $this->assertEquals($nbCategoriesAvant + 1, $nbCategoriesApres, "Erreur lors de l'ajout de la catégorie");
    }
    
    public function testRemoveCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie);
        $nbCategoriesAvant = $repository->count([]);
        $repository->remove($categorie);
        $nbCategoriesApres = $repository->count([]);
        $this->assertEquals($nbCategoriesAvant - 1, $nbCategoriesApres, "Erreur lors de la suppression de la catégorie");
    }
    
}
