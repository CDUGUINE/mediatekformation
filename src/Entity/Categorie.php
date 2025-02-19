<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Entité catégorie
 * Définit les propriétés et méthodes des catégories
 * @author cdugu
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[UniqueEntity(fields: ['name'], message: "Ce titre existe déjà.")]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(
    max: 50,
    maxMessage: "Le titre ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $name = null;

    /**
     * @var Collection<int, Formation>
     */
    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'categories')]
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retoure les formations liées à la catégorie
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * Ajoute une formation à la catégorie
     * @param Formation $formation
     * @return static
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->addCategory($this);
        }

        return $this;
    }

    /**
     * Supprime une formation de la catégorie
     * @param Formation $formation
     * @return static
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeCategory($this);
        }

        return $this;
    }
}
