<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository des catégories
 * @extends ServiceEntityRepository<Categorie>
 * @author cdugu
 */
class CategorieRepository extends ServiceEntityRepository
{
    /**
     * Constructeur
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }
    /**
     * Persistance de l'ajout d'une catégorie dans la bdd
     * @param Categorie $entity
     * @return void
     */
    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Persistance de la suppression d'une catégorie dans la bdd
     * @param Categorie $entity
     * @return void
     */
    public function remove(Categorie $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne la liste des catégories des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array
    {
        return $this->createQueryBuilder('c')
                ->join('c.formations', 'f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('c.name', 'ASC')
                ->getQuery()
                ->getResult();
    }
    
}
