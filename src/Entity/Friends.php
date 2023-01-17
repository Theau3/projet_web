<?php

namespace App\Entity;

use App\Repository\FriendsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
use App\Entity\Notification;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: FriendsRepository::class)]
class Friends
{
    #[ORM\Id] //clé primaire
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id_amis = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    private $status;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

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

    public function getIdAmis(): ?int
    {
        return $this->id_amis;
    }

    public function getpp()
    {
        return $this->user2->getPp();
    }

    public function getusername1()
    {
        return $this->user1->getUsername();
    }

    public function getusername()
    {
        return $this->user2->getUsername();
    }

    public function getLevel(int $experience)
    {
        $level = 0.436 * ($experience ** 0.323);
        return floor($level);
    }

    public function getLevelPercentage(int $level, int $progression)
    {
        $xp_for_next_level = $this->user2->getExperience($level + 1) - $this->user2->getExperience($level);
        $xp_from_level = $progression - $this->user2->getExperience($level);
        $level_percentage = ($xp_from_level / $xp_for_next_level) * 100;
        return intval($level_percentage);
    }

    public function getProgression(): ?int
    {
        return $this->user2->getProgression();
    }
}
