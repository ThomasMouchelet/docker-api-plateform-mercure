<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[ApiResource(
    mercure:true,
    normalizationContext:['groups' => ['read:user:collection']],
    denormalizationContext: ['groups' => ['write:user']],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:user:item', 
                ]]
        ]
    ]
)]
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        "read:user:collection",
        "read:homework:collection",
        "read:homework:item",
        "read:user:item",
        "students_subresource",
        "read:student:collection",
        "read:student:collection",
        "homeworks_subresource",
        "read:classroom:item",
        "read:student:item",
        "read:invitation:collection",
        'put:inscription:item',
        'read:classroom:collection'
        ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Groups([
        'read:user:collection',
        'read:homework:collection',
        'read:homework:item',
        'read:homework:item',
        'read:user:item',
        'students_subresource',
        'read:student:item',
        'read:invitation:collection',
        'write:user'
        ])]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups([
        'read:user:collection',
        'read:user:item',
        'read:student:item',
        'write:user'
        ])]
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[Groups([
        'write:user'
        ])]
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups([
        "read:user:collection",
        "read:homework:collection",
        "homeworks_subresource",
        "read:homework:item",
        "read:homework:item",
        "read:user:item",
        "students_subresource",
        "read:classroom:item",
        "read:student:item",
        "read:invitation:collection",
        'write:user',
        'read:classroom:collection'
        ])]
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups([
        'read:user:collection',
        'read:homework:collection',
        'homeworks_subresource',
        'read:homework:item',
        'read:homework:item',
        'read:user:item',
        'students_subresource',
        'read:classroom:item',
        'read:student:item',
        'read:invitation:collection',
        'write:user',
        'read:classroom:collection'
        ])]
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups([
        'read:user:collection',
        'read:user:item',
        'read:student:item',
        'write:user'
        ])]
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity=Teacher::class, mappedBy="user", cascade={"persist", "remove"})
     */
    #[Groups([
        'read:user:collection',
        'read:user:item',
        'read:invitation:collection',
        'read:inscription:item',
        'write:user',
        'put:inscription:item'
        ])]
    private $teacher;

    /**
     * @ORM\OneToOne(targetEntity=Student::class, mappedBy="user", cascade={"persist", "remove"})
     */
    #[Groups([
        "read:user:collection",
        "read:user:item",
        "read:invitation:collection",
        "read:inscription:item",
        'write:user',
        'put:inscription:item'
        ])]
    private $student;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    #[Groups(["read:user:collection"])]
    private $dbroles;

    /**
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="owner")
     */
    private $invitations;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="userRegister")
     */
    private $inscriptions;

    public function __construct()
    {
        $this->dbroles = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
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
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        $dbroles = $this->getDbroles();

        foreach ($dbroles as $role) {
            array_push($roles, $role->getType());
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $roles[] = 'ROLE_USER';
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        // unset the owning side of the relation if necessary
        if ($teacher === null && $this->teacher !== null) {
            $this->teacher->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($teacher !== null && $teacher->getUser() !== $this) {
            $teacher->setUser($this);
        }

        $this->teacher = $teacher;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        // unset the owning side of the relation if necessary
        if ($student === null && $this->student !== null) {
            $this->student->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($student !== null && $student->getUser() !== $this) {
            $student->setUser($this);
        }

        $this->student = $student;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getDbroles(): Collection
    {
        return $this->dbroles;
    }

    public function addDbrole(Role $dbrole): self
    {
        if (!$this->dbroles->contains($dbrole)) {
            $this->dbroles[] = $dbrole;
            $dbrole->addUser($this);
        }

        return $this;
    }

    public function removeDbrole(Role $dbrole): self
    {
        if ($this->dbroles->removeElement($dbrole)) {
            $dbrole->removeUser($this);
        }

        return $this;
    }

    public function getInitials() : string
    {
        $fullName  = $this->firstName . $this->lastName;
        $words = explode(' ', $fullName);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        return $this->makeInitialsFromSingleWord($fullName);
    }

    /**
     * Make initials from a word with no spaces
     *
     * @param string $name
     * @return string
     */
    protected function makeInitialsFromSingleWord(string $name) : string
    {
        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return substr(implode('', $capitals[1]), 0, 2);
        }
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * @return Collection|Invitation[]
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations[] = $invitation;
            $invitation->setOwner($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getOwner() === $this) {
                $invitation->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setUserRegister($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getUserRegister() === $this) {
                $inscription->setUserRegister(null);
            }
        }

        return $this;
    }

}