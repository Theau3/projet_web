<?php
// src/Entity/Task.php


namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    public ?string $Titre = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    public ?string $Description = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'tasks')]
    #[Assert\Valid]
    protected $category;

    #[ORM\Column]
    private ?int $avancement = null;

    #[ORM\Column]
    private ?bool $Perso = null;

    private ?int $ChoixTemps = null;

    private ?int $NombreFois = null;



    public function getChoixTemps(): ?int
    {
        return $this->ChoixTemps;
    }

    public function setChoixTemps(int $ChoixTemps): self
    {
        $this->ChoixTemps = $ChoixTemps;

        return $this;
    }

    public function getNombreFois(): ?int
    {
        return $this->NombreFois;
    }

    public function setNombreFois(int $NombreFois): self
    {
        $this->NombreFois = $NombreFois;

        return $this;
    }

    public function getIdTask(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->Titre; //On retourne la tâche associée à l'objet courant
    }

    public function setTitre(string $Titre): void
    {
        $this->Titre = $Titre; //la tâche de l'objet courant associé prend la valeur de tâche définie.
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): void
    {
        $this->Description = $Description;
    }

    public function getPerso(): bool
    {
        return $this->Perso;
    }

    public function setPerso(bool $Perso): void
    {
        $this->Perso = $Perso;
    }

    public function getAvancement(): int
    {
        return $this->avancement;
    }

    public function setAvancement(int $avancement): void
    {
        $this->avancement = $avancement;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isPerso(): ?bool
    {
        return $this->Perso;
    }
}