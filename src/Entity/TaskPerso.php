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
}