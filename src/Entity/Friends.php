<?php

namespace App\Entity;

use App\Repository\FriendsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

#[ORM\Entity(repositoryClass: FriendsRepository::class)]
class Friends
{
    #[ORM\Id] //clé primaire
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_amis = null;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Assert\Valid]
    protected $user1;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Assert\Valid]
    protected $user2;

    public function getUser1(): ?User
    {
        return $this->user1;
    }

    public function setUser1(?User $user1): self
    {
        $this->user1 = $user1;

        return $this;
    }

    public function getUser2(): ?User
    {
        return $this->user1;
    }

    public function setUser2(?User $user2): self
    {
        $this->user2 = $user2;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id_amis;
    }

    public function getpp()
    {
        return $this->user2->getPp();
    }

    public function getusername()
    {
        return $this->user2->getUsername();
    }
}
