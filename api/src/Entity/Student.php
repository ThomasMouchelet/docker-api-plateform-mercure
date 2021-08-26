<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
#[ApiResource(
    mercure:true,
    subresourceOperations: ['api_classrooms_students_get_subresource' => [
        'normalization_context' => ['groups' => ['students_subresource']]
    ]],
    normalizationContext:['groups' => ['read:student:collection']],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:student:item', 
                ]]
        ]
    ]
)]
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'read:student:collection',
        'students_subresource',
        'read:homework:item',
        'read:classroom:item',
        'read:student:item',
        'write:user'
        ])]
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="student", cascade={"persist", "remove"})
     */
    #[Groups(["read:student:collection","students_subresource","read:homework:item","read:classroom:item","read:student:item"])]
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Delivrable::class, mappedBy="student")
     */
    #[Groups(["read:student:collection","students_subresource","read:student:item"])]
    private $delivrables;

    /**
     * @ORM\ManyToMany(targetEntity=Classroom::class, mappedBy="students")
     */
    #[Groups(["read:student:collection","students_subresource","read:student:item"])]
    private $classrooms;

    public function __construct()
    {
        $this->delivrables = new ArrayCollection();
        $this->classrooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Delivrable[]
     */
    public function getDelivrables(): Collection
    {
        return $this->delivrables;
    }

    public function addDelivrable(Delivrable $delivrable): self
    {
        if (!$this->delivrables->contains($delivrable)) {
            $this->delivrables[] = $delivrable;
            $delivrable->setStudent($this);
        }

        return $this;
    }

    public function removeDelivrable(Delivrable $delivrable): self
    {
        if ($this->delivrables->removeElement($delivrable)) {
            // set the owning side to null (unless already changed)
            if ($delivrable->getStudent() === $this) {
                $delivrable->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Classroom[]
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(Classroom $classroom): self
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms[] = $classroom;
            $classroom->addStudent($this);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): self
    {
        if ($this->classrooms->removeElement($classroom)) {
            $classroom->removeStudent($this);
        }

        return $this;
    }
}