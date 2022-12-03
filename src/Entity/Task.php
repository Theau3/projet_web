<?php
// src/Entity/Task.php


namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


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
    private ?bool $Perso = null;
    


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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}