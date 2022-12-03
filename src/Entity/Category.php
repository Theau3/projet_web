<?php
// src/Entity/Category.php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\MockObject\Builder\Identity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $nom = null;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'category')]
    private $tasks; //Pour accéder aux tâches d'une catégorie.


    public function getIdCat(): ?int
    {
        return $this->id;
    }

    public function getNomCat(): string
    {
        return $this->nom; 
    }

    public function setNomCat(string $nom): void
    {
        $this->nom = $nom; 
    }

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @return Collection|Product[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

}