<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Homework;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
#[ApiResource(
    normalizationContext:['groups' => ['read:teacher:collection']],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:teacher:item', 
                ]]
        ]
    ]
)]
class Teacher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'read:teacher:collection',
        'read:homework:collection', 
        'read:user:collection',
        'homeworks_subresource',
        'read:homework:item',
        'read:teacher:item',
        'write:user'
        ])]
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="teacher", cascade={"persist", "remove"})
     */
    #[Groups(["read:teacher:collection","read:homework:collection","homeworks_subresource","read:homework:item","read:teacher:item"])]
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Subject::class, mappedBy="teacher")
     */
    #[Groups(["read:teacher:collection", "read:user:collection","read:teacher:item"])]
    private $subjects;

    /**
     * @ORM\ManyToMany(targetEntity=Classroom::class, inversedBy="teacher")
     */
    #[Groups(["read:teacher:collection", "read:user:collection","read:teacher:item"])]
    private $classrooms;

    /**
     * @ORM\OneToMany(targetEntity=Homework::class, mappedBy="teacher")
     */
    #[Groups(["read:teacher:collection", "read:user:collection","read:teacher:item"])]
    private $homeworks;

    /**
     * @ORM\OneToMany(targetEntity=HomeworkFile::class, mappedBy="teacher")
     */
    private $homeworkFiles;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->classrooms = new ArrayCollection();
        $this->homeworks = new ArrayCollection();
        $this->homeworkFiles = new ArrayCollection();
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
     * @return Collection|Subject[]
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
            $subject->setTeacher($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getTeacher() === $this) {
                $subject->setTeacher(null);
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
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): self
    {
        $this->classrooms->removeElement($classroom);

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
            $homework->setTeacher($this);
        }

        return $this;
    }

    public function removeHomework(Homework $homework): self
    {
        if ($this->homeworks->removeElement($homework)) {
            // set the owning side to null (unless already changed)
            if ($homework->getTeacher() === $this) {
                $homework->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HomeworkFile[]
     */
    public function getHomeworkFiles(): Collection
    {
        return $this->homeworkFiles;
    }

    public function addHomeworkFile(HomeworkFile $homeworkFile): self
    {
        if (!$this->homeworkFiles->contains($homeworkFile)) {
            $this->homeworkFiles[] = $homeworkFile;
            $homeworkFile->setTeacher($this);
        }

        return $this;
    }

    public function removeHomeworkFile(HomeworkFile $homeworkFile): self
    {
        if ($this->homeworkFiles->removeElement($homeworkFile)) {
            // set the owning side to null (unless already changed)
            if ($homeworkFile->getTeacher() === $this) {
                $homeworkFile->setTeacher(null);
            }
        }

        return $this;
    }

}
