<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'Il y a déja un compte avec ce nom d\'utilisateur veuillez en choisir un autre')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $progression = 0;

    #[ORM\Column(length: 180, unique: false)]
    private ?string $pp = 'pp1.png';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getProgression(): ?int
    {
        return $this->progression;
    }

    public function getPp(): ?string
    {
        return $this->pp;
    }

    public function setProgression(int $progression): self
    {
        $this->progression = $progression;
        return $this;
    }

    public function setPp(string $pp): self
    {
        $this->pp = $pp;
        return $this;
    }

    //Mapping de User à tâche perso
    #[ORM\OneToMany(targetEntity: TaskPerso::class, mappedBy: 'user')]
    private $tasksPerso;

    public function __construct()
    {
        $this->tasksPerso = new ArrayCollection();
    }

    /**
     * @return Collection|TaskPerso[]
     */
    public function getTasksPerso(): Collection
    {
        return $this->tasksPerso;
    }

    public function addTasksPerso(TaskPerso $tasksPerso): self
    {
        if (!$this->tasksPerso->contains($tasksPerso)) {
            $this->tasksPerso->add($tasksPerso);
            $tasksPerso->setUser($this);
        }

        return $this;
    }

    public function removeTasksPerso(TaskPerso $tasksPerso): self
    {
        if ($this->tasksPerso->removeElement($tasksPerso)) {
            // set the owning side to null (unless already changed)
            if ($tasksPerso->getUser() === $this) {
                $tasksPerso->setUser(null);
            }
        }

        return $this;
    }

    public function getExperience(int $level){
        $progression = 13.1*($level**3.1);
        return $progression;
    }

    public function getLevel(int $experience){
        $level = 0.436*($experience**0.323);
        return floor($level);
    }

    public function getLevelPercentage(int $level,int $progression){
        $xp_for_next_level = $this->getExperience($level+1) - $this->getExperience($level);
        $xp_from_level = $progression - $this->getExperience($level);
        $level_percentage = ($xp_from_level / $xp_for_next_level)*100;
        return intval($level_percentage);
    }

    public function getTitre(int $level){
        if ($level < 10) {
            $titre = "débutant";
        }elseif ($level < 20) {
            $titre = "novice";
        }elseif ($level < 30) {
            $titre = "intermédiaire";
        }elseif ($level < 40) {
            $titre = "avancé";
        }else{
            $titre = "expert";
        }
        return $titre;
    }


}