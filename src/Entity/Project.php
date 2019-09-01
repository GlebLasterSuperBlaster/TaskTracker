<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="projects")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdProjects")
     */
    private $createdBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="projectsInvitedTo")
     */
    private $invitedUsers;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $token;


    public function __construct()
    {
        $this->invites = new ArrayCollection();
        $this->usersThatInProject = new ArrayCollection();
        $this->invited_user = new ArrayCollection();
        $this->invitedUsers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }


    /**
     * @ORM\PrePersist
     */

    public function setCreatedAt(): self
    {
        if(isset($this->created_at2))
            $this->created_at = $this->created_at2;
        else
            $this->created_at = new \DateTime();
        return $this;
    }


    public function setCreatedAtForFixtures($created_at): self
    {
        $this->created_at2 = $created_at;

        return $this;

    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getInvitedUsers(): Collection
    {
        return $this->invitedUsers;
    }

    public function addInvitedUser(User $invitedUser): self
    {
        if (!$this->invitedUsers->contains($invitedUser)) {
            $this->invitedUsers[] = $invitedUser;
        }

        return $this;
    }

    public function removeInvitedUser(User $invitedUser): self
    {
        if ($this->invitedUsers->contains($invitedUser)) {
            $this->invitedUsers->removeElement($invitedUser);
        }
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }


}
