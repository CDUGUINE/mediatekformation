<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository des formations
 * @extends ServiceEntityRepository<Formation>
 * @author cdugu
 */
class FormationRepository extends ServiceEntityRepository
{
    /**
     * Constructeur
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Persistance de l'ajout d'une formation dans la bdd
     * @param Formation $entity
     * @return void
     */
    public function add(Formation $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Persistance de la suppression d'une formation dans la bdd
     * @param Formation $entity
     * @return void
     */
    public function remove(Formation $entity): void
    {
        $playlist = $entity->getPlaylist();
        
        // Retirer la formation de la playlist
        if ($playlist){
            $playlist->getFormations()->removeElement($entity);
        }
        
        // Supprimer l'entité Formation
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les formations triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @param type $table si $champ dans une autre table
     * @return Formation[]
     */
    public function findAllOrderBy($champ, $ordre, $table=""): array
    {
        if ($table=="") {
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
        } else {
            return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->orderBy('t.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
        }
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Formation[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array
    {
        if ($valeur=="") {
            return $this->findAll();
        }
        if ($table=="") {
            return $this->createQueryBuilder('f')
                    ->where('f.'.$champ.' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();
        } else {
            return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->where('t.'.$champ.' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();
        }
    }
    
    /**
     * Retourne les n formations les plus récentes
     * @param type $nb
     * @return Formation[]
     */
    public function findAllLasted($nb) : array
    {
        return $this->createQueryBuilder('f')
                ->orderBy('f.publishedAt', 'DESC')
                ->setMaxResults($nb)
                ->getQuery()
                ->getResult();
    }
    
    /**
     * Retourne la liste des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array
    {
        return $this->createQueryBuilder('f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('f.publishedAt', 'ASC')
                ->getQuery()
                ->getResult();
    }
    
}
