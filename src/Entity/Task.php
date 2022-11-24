<?php
// src/Entity/Task.php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Task
{
    #[Assert\NotBlank]
    public $Titre;
    #[Assert\NotBlank]
    public $Description;
    #[Assert\NotBlank]
    #[Assert\Type(\DateTime::class)]
    protected $dueDate;

    public function getTask(): string
    {
        return $this->task; //On retourne la tâche associée à l'objet courant
    }

    public function setTask(string $Titre): void
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

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }
}