<?php

namespace App\Entity;

use App\Repository\HomeworkFileRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\HomeworkFileController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

/**
 * @ORM\Entity(repositoryClass=HomeworkFileRepository::class)
 * @Vich\Uploadable()
 */
#[ApiResource(
    mercure:true,
    normalizationContext:['groups' => ['read:homeworkFile:collection']],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:homeworkFile:item', 
                ]]
        ]
    ],
    collectionOperations:[
        'file' => [
            'method' => 'post',
            'path' => '/homeworks/{id}/files',
            'controller' => HomeworkFileController::class, 
            'deserialize' => false,
        ],
    ]
)]
class HomeworkFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'read:homeworkFile:collection',
        'read:homeworkFile:item',
        'read:homework:item'
    ])]
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="homeworkFiles")
     */
    #[Groups([
        'read:homeworkFile:collection',
        'read:homeworkFile:item',
    ])]
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity=Homework::class, inversedBy="homeworkFiles")
     */
    #[Groups([
        'read:homeworkFile:collection',
        'read:homeworkFile:item',
    ])]
    private $homework;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups([
        'read:homeworkFile:collection',
        'read:homeworkFile:item',
    ])]
    private $uploadedAt;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="homework_file", fileNameProperty="filePath")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups([
        'read:homeworkFile:collection',
        'read:homeworkFile:item',
        'read:homework:item'
    ])]
    private $fileUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups([
        'read:homeworkFile:collection',
        'read:homeworkFile:item',
        'read:homework:item'
    ])]
    private $filePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups([
        'read:homeworkFile:collection',
        'read:homeworkFile:item',
    ])]
    private $fileSlug;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHomework(): ?Homework
    {
        return $this->homework;
    }

    public function setHomework(?Homework $homework): self
    {
        $this->homework = $homework;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeInterface $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    public function setFileUrl(?string $fileUrl): self
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $this->cleanString($filePath);

        return $this;
    }

    public function getFileSlug(): ?string
    {
        return $this->fileSlug;
    }

    public function setFileSlug(?string $fileSlug): self
    {
        $this->fileSlug = $this->cleanString($fileSlug);

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
