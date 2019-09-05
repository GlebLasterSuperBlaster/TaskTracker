<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message="Please input project title")
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=3, minMessage="Min 3 symbols required for project title")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Please input project description")
     * @Assert\Length(min=5, minMessage="Min 5 symbols required for project description")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    private $createdAt2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdProjects")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="projectsInvitedTo")
     */
    private $invitedUsers;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="project", orphanRemoval=true)
     */
    private $tasks;

    public function __construct()
    {

        $this->invitedUsers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): self
    {
        if(isset($this->createdAt2))
            $this->createdAt = $this->createdAt2;
        else
            $this->createdAt = new \DateTime();
        return $this;
    }


    public function setCreatedAtForFixtures($created_at): self
    {
        $this->createdAt2 = $created_at;

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


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate()
     * @param \DateTimeInterface|null $updatedAt
     * @return Project
     * @throws \Exception
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }


    public function getAllProjects()
    {

    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }
}
