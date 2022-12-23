<?php
// src/Entity/TaskPerso.php


namespace App\Entity;

use App\Repository\TaskRepository;
use App\Repository\TaskPersoRepository;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: TaskPersoRepository::class)]
class TaskPerso
{
    #[ORM\Id]//clé primaire
    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[Assert\Valid]
    protected $Task; //On récupère la classe Task

    #[ORM\Id]//clé primaire
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasksPerso')]
    #[Assert\Valid]
    protected $user;


    #[ORM\Column]
    private ?int $repetition = null;
    #[Assert\Type(\DateTime::class)]
    #[ORM\Column]
    protected ?DateTime $dueDate = null;
    #[Assert\Type(\DateTime::class)]
    #[ORM\Column]
    protected ?DateTime $dateDebut = null;

    #[ORM\Column]
    private ?bool $isDone = null;
    #[ORM\Column]
    private ?bool $isFavoris = null;



    public function __construct()
    {
        $this->dueDate = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->dateDebut = new DateTime('now', new DateTimeZone('Europe/Paris'));
    }



    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(?bool $isDone): void
    {
        $this->isDone = $isDone;
    }

    public function getIsFavoris(): ?bool
    {
        return $this->isFavoris;
    }

    public function setIsFavoris(?bool $isFavoris): void
    {
        $this->isFavoris = $isFavoris;
    }
    

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function getRepetition(): int
    {
        return $this->repetition;
    }

    public function setRepetition(int $repetition): void
    {
        $this->repetition = $repetition;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function getTask(): ?Task
    {
        return $this->Task;
    }

    public function setTask(?Task $Task): self
    {
        $this->Task = $Task;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function gettask_id() 
    {
        return $this->Task->getId();
    }

    public function getuser_id(){
        return $this->user->getId();
    }


    // Définissez une méthode pour récupérer la tâche précédente
    public function getPreviousTask($tasks, int $currentTaskIndex)
    {
        // Si l'index est supérieur à 0 (ce qui signifie qu'il existe une tâche précédente)
        if ($currentTaskIndex > 0) {
            // Réduisez l'index de 1 pour passer à la tâche précédente
            $currentTaskIndex--;

            // Retournez la tâche précédente
            return $tasks[$currentTaskIndex];
        }

        // Si l'index est égal à 0 (ce qui signifie qu'il n'y a pas de tâche précédente), retournez null
        return null;
    }

    // Définissez une méthode pour récupérer la tâche suivante
    public function getNextTask($tasks, int $currentTaskIndex)
    {
        // Si l'index est supérieur à 0 (ce qui signifie qu'il existe une tâche suivante)
        if ($currentTaskIndex < count($tasks)-1) {
            // augmenter l'index de 1 pour passer à la tâche précédente
            $currentTaskIndex++;

            // Retournez la tâche précédente
            return $tasks[$currentTaskIndex];
        }

        // Si l'index est égal à 0 (ce qui signifie qu'il n'y a pas de tâche précédente), retournez null
        return null;
    }

}