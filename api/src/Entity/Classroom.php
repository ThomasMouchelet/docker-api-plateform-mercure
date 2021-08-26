<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClassroomRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ClassroomRepository::class)
 */
#[ApiResource(
    mercure:true,
    subresourceOperations:[
        "students_get_subresource" => ["path"=>"/classrooms/{id}/students"],
    ],
    itemOperations:["PUT", "DELETE",
        'get' => [
            'normalization_context' => ['groups' => [
                'read:classroom:item', 
                ]]
        ],
       "get_classroom_students" => [
         "method" => "get",
         "path" => "/classrooms/{id}/students", 
         "controller" => "App\Controller\ClassroomStudentsController", 
         "swagger_context" => [
            "summary" => "Get all students",
            "description" => "Get all students"
            ],
         "normalization_context" =>["groups" =>["students_subresource"]],
        ],
       "get_classroom_homeworks" =>[
         "method" => "get",
         "path" => "/classrooms/{id}/homeworks", 
         "controller" => "App\Controller\ClassroomHomeworksController",
         "normalization_context" => ["groups" => ["homeworks_subresource"]],
       ],
    ],
    normalizationContext:["groups" => ["read:classroom:collection"]],
    denormalizationContext: ["disable_type_enforcement" => true]
)]
#[ApiFilter(
    SearchFilter::class, properties:["school"]
)]
class Classroom
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'read:classroom:collection',
        'read:classroom:item',
        'read:invitation:collection',
        ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups([
        'read:classroom:collection',
        'read:classroom:item',
        'read:invitation:collection'
        ])]
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="classrooms")
     */
    #[Groups([
        'read:classroom:collection',
        'read:classroom:item',
        'read:invitation:collection'
        ])]
    private $school;

    /**
     * @ORM\OneToMany(targetEntity=Subject::class, mappedBy="classroom")
     */
    #[Groups(["read:classroom:collection","read:classroom:item"])]
    private $subjects;

    /**
     * @ORM\ManyToMany(targetEntity=Teacher::class, mappedBy="classrooms")
     */
    #[Groups(["read:classroom:collection","read:classroom:item"])]
    private $teachers;

    /**
     * @ORM\OneToMany(targetEntity=Homework::class, mappedBy="classroom")
     */
    #[Groups(["read:classroom:collection","read:classroom:item"])]
    private $homeworks;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["read:classroom:collection","read:classroom:item"])]
    private $invitationCode;

    /**
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="classroom")
     */
    #[Groups(["read:classroom:collection","read:classroom:item"])]
    private $invitations;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, inversedBy="classrooms")
     */
    #[Groups(["read:classroom:collection","read:classroom:item"])]
    private $students;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->homeworks = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->students = new ArrayCollection();
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
        $this->setSlug();

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

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
            $subject->setClassroom($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getClassroom() === $this) {
                $subject->setClassroom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Teacher[]
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Teacher $teacher): self
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers[] = $teacher;
            $teacher->addClassroom($this);
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): self
    {
        if ($this->teachers->removeElement($teacher)) {
            $teacher->removeClassroom($this);
        }

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
            $homework->setClassroom($this);
        }

        return $this;
    }

    public function removeHomework(Homework $homework): self
    {
        if ($this->homeworks->removeElement($homework)) {
            // set the owning side to null (unless already changed)
            if ($homework->getClassroom() === $this) {
                $homework->setClassroom(null);
            }
        }

        return $this;
    }

    public function getInvitationCode(): ?string
    {
        return $this->invitationCode;
    }

    public function setInvitationCode(?string $invitationCode): self
    {
        $this->invitationCode = $invitationCode;

        return $this;
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
            $invitation->setClassroom($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getClassroom() === $this) {
                $invitation->setClassroom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        $this->students->removeElement($student);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(): self
    {
        $this->slug = $this->cleanString($this->name);

        return $this;
    }

    protected function cleanString($text) {
        $text = strtolower($text);
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[óòôõºö]/u'   =>   'o',
            '/[úùûü]/u'     =>   'u', 
            '/ç/'           =>   'c',
            '/ñ/'           =>   'n',
            '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   '', // Literally a single quote
            '/[“”«»„]/u'    =>   '', // Double quote
            '/ /'           =>   '_',
            '/:/'           =>   '',
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
}