<?php

namespace App\Entity;

use App\Repository\HomeworkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=HomeworkRepository::class)
 */
#[ApiResource(
    attributes:["order"=> ["createdAt" => "DESC"]],
    mercure:true,
    shortName:"homeworks",
    subresourceOperations:['api_classrooms_homeworks_get_subresource' => [
        'normalization_context' => ['groups' => ['homeworks_subresource']]
    ]],
    normalizationContext:['groups' => ['read:homework:collection']],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:homework:item', 
                ]]
        ]
    ]
)]
class Homework
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'read:homework:collection',
        'homeworks_subresource',
        'read:homework:item',
        'read:delivrable:item',
        'read:delivrable:collection'
        ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read:homework:collection","homeworks_subresource","read:homework:item"])]
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups(["read:homework:collection","homeworks_subresource","read:homework:item"])]
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(["read:homework:collection","homeworks_subresource","read:homework:item"])]
    private $endingDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(["read:homework:collection","homeworks_subresource","read:homework:item"])]
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Delivrable::class, mappedBy="homework")
     */
    #[Groups(["read:homework:collection","homeworks_subresource","read:homework:item"])]
    private $delivrables;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["read:homework:collection","homeworks_subresource","read:homework:item"])]
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Classroom::class, inversedBy="homeworks")
     */
    #[Groups(["read:homework:collection","read:homework:item"])]
    private $classroom;

    /**
     * @ORM\ManyToOne(targetEntity=Subject::class, inversedBy="homeworks")
     */
    #[Groups(["read:homework:collection","homeworks_subresource","read:homework:item"])]
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="homeworks")
     */
    #[Groups(["read:homework:collection","homeworks_subresource","read:homework:item"])]
    private $teacher;

    /**
     * @ORM\OneToMany(targetEntity=HomeworkFile::class, mappedBy="homework")
     */
    #[Groups(["read:homework:collection","read:homework:item"])]
    private $homeworkFiles;

    public function __construct()
    {
        $this->delivrables = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->homeworkFiles = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->endingDate;
    }

    public function setEndingDate(?\DateTimeInterface $endingDate): self
    {
        $this->endingDate = $endingDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
            $delivrable->setHomework($this);
        }

        return $this;
    }

    public function removeDelivrable(Delivrable $delivrable): self
    {
        if ($this->delivrables->removeElement($delivrable)) {
            // set the owning side to null (unless already changed)
            if ($delivrable->getHomework() === $this) {
                $delivrable->setHomework(null);
            }
        }

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

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

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

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
            $homeworkFile->setHomework($this);
        }

        return $this;
    }

    public function removeHomeworkFile(HomeworkFile $homeworkFile): self
    {
        if ($this->homeworkFiles->removeElement($homeworkFile)) {
            // set the owning side to null (unless already changed)
            if ($homeworkFile->getHomework() === $this) {
                $homeworkFile->setHomework(null);
            }
        }

        return $this;
    }

  
}
