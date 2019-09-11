<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users")
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Please input the correct email")
     * @Assert\Email()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="Please input your password")
     * @Assert\Length(max=4096)
     * @Assert\Length(min=4, minMessage="Min 4 symbols required")
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Please input your name")
     * @Assert\Length(min=3, minMessage="Min 3 symbols required for name")
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Please input your last name")
     * @Assert\Length(min=3, minMessage="Min 3 symbols required for lastName")
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="createdBy")
     */
    private $createdProjects;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Project", mappedBy="invitedUsers")
     */
    private $projectsInvitedTo;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="createdBy")
     */
    private $createdTasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="executor")
     */
    private $executedTasks;

    public function __construct()
    {
        $this->createdProjects = new ArrayCollection();
        $this->projectsInvitedTo = new ArrayCollection();
        $this->createdTasks = new ArrayCollection();
        $this->executedTasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getInvitedUser(): Collection
    {
        return $this->invited_user;
    }

    public function addInvitedUser(Project $invitedUser): self
    {
        if (!$this->invited_user->contains($invitedUser)) {
            $this->invited_user[] = $invitedUser;
            $invitedUser->addInvitedUser($this);
        }

        return $this;
    }

    public function removeInvitedUser(Project $invitedUser): self
    {
        if ($this->invited_user->contains($invitedUser)) {
            $this->invited_user->removeElement($invitedUser);
            $invitedUser->removeInvitedUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getCreatorUser(): Collection
    {
        return $this->creator_user;
    }

    public function addCreatorUser(Project $creatorUser): self
    {
        if (!$this->creator_user->contains($creatorUser)) {
            $this->creator_user[] = $creatorUser;
            $creatorUser->setUser($this);
        }

        return $this;
    }

    public function removeCreatorUser(Project $creatorUser): self
    {
        if ($this->creator_user->contains($creatorUser)) {
            $this->creator_user->removeElement($creatorUser);
            // set the owning side to null (unless already changed)
            if ($creatorUser->getUser() === $this) {
                $creatorUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getCreatedProjects(): Collection
    {
        return $this->createdProjects;
    }

    public function addCreatedProject(Project $createdProject): self
    {
        if (!$this->createdProjects->contains($createdProject)) {
            $this->createdProjects[] = $createdProject;
            $createdProject->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedProject(Project $createdProject): self
    {
        if ($this->createdProjects->contains($createdProject)) {
            $this->createdProjects->removeElement($createdProject);
            // set the owning side to null (unless already changed)
            if ($createdProject->getCreatedBy() === $this) {
                $createdProject->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjectsInvitedTo(): Collection
    {
        return $this->projectsInvitedTo;
    }

    public function addProjectsInvitedTo(Project $projectsInvitedTo): self
    {
        if (!$this->projectsInvitedTo->contains($projectsInvitedTo)) {
            $this->projectsInvitedTo[] = $projectsInvitedTo;
            $projectsInvitedTo->addInvitedUser($this);
        }

        return $this;
    }

    public function removeProjectsInvitedTo(Project $projectsInvitedTo): self
    {
        if ($this->projectsInvitedTo->contains($projectsInvitedTo)) {
            $this->projectsInvitedTo->removeElement($projectsInvitedTo);
            $projectsInvitedTo->removeInvitedUser($this);
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

    /**
     * @return Collection|Task[]
     */
    public function getCreatedTasks(): Collection
    {
        return $this->createdTasks;
    }

    public function addCreatedTask(Task $createdTask): self
    {
        if (!$this->createdTasks->contains($createdTask)) {
            $this->createdTasks[] = $createdTask;
            $createdTask->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedTask(Task $createdTask): self
    {
        if ($this->createdTasks->contains($createdTask)) {
            $this->createdTasks->removeElement($createdTask);
            // set the owning side to null (unless already changed)
            if ($createdTask->getCreatedBy() === $this) {
                $createdTask->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getExecutedTasks(): Collection
    {
        return $this->executedTasks;
    }

    public function addExecutedTask(Task $executedTask): self
    {
        if (!$this->executedTasks->contains($executedTask)) {
            $this->executedTasks[] = $executedTask;
            $executedTask->setExecutor($this);
        }

        return $this;
    }

    public function removeExecutedTask(Task $executedTask): self
    {
        if ($this->executedTasks->contains($executedTask)) {
            $this->executedTasks->removeElement($executedTask);
            // set the owning side to null (unless already changed)
            if ($executedTask->getExecutor() === $this) {
                $executedTask->setExecutor(null);
            }
        }

        return $this;
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail()
        ];
    }

}
