<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=SubjectRepository::class)
 */
#[ApiResource()]
class Subject
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Classroom::class, inversedBy="subjects")
     */
    private $classroom;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="subjects")
     */
    private $teacher;

    /**
     * formType input
     */
    private $fileInput;

    /**
     * @ORM\OneToMany(targetEntity=Homework::class, mappedBy="subject")
     */
    private $homeworks;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->homeworks = new ArrayCollection();
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

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getFileInput()
    {
        return $this->fileInput;
    }

    public function setFileInput($fileInput): self
    {
        $this->fileInput = $fileInput;

        return $this;
    }

    /**
     * @return Collection|Homework[]
     */
    public function getHomeworks(): Collection
    {
        return $this->homeworks;
    }

    public function addHomework(Homework $homework): self
    {
        if (!$this->homeworks->contains($homework)) {
            $this->homeworks[] = $homework;
            $homework->setSubject($this);
        }

        return $this;
    }

    public function removeHomework(Homework $homework): self
    {
        if ($this->homeworks->removeElement($homework)) {
            // set the owning side to null (unless already changed)
            if ($homework->getSubject() === $this) {
                $homework->setSubject(null);
            }
        }

        return $this;
    }
}
