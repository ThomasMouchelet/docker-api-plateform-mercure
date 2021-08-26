<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\DelivrableController;
use App\Repository\DelivrableRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

/**
 * @ORM\Entity(repositoryClass=DelivrableRepository::class)
 * @Vich\Uploadable()
 */
#[ApiResource(
    mercure:true,
    normalizationContext:['groups' => ['read:delivrable:collection']],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:delivrable:item', 
                ]]
        ]
    ],
    collectionOperations:[
        'delivrable' => [
            'method' => 'post',
            'path' => '/homeworks/{id}/delivrables',
            'controller' => DelivrableController::class, 
            'deserialize' => false,
        ],
    ]
)]

class Delivrable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'read:delivrable:collection',
        'read:delivrable:item',
        'read:homework:item'
        ])]
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="delivrables")
     */
    #[Groups([
        'read:delivrable:collection',
        'read:delivrable:item',
        'read:homework:item'
        ])]
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=Homework::class, inversedBy="delivrables")
     */
    #[Groups([
        'read:delivrable:collection',
        'read:delivrable:item',
        ])]
    private $homework;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    #[Groups([
        'read:delivrable:collection',
        'read:delivrable:item',
        ])]
    private $rating;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="delivrable_file", fileNameProperty="filePath")
     */
    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups([
        'read:delivrable:collection',
        'read:delivrable:item',
        'read:homework:item'
        ])]
    private $uploadedAt;

    /**
     * @var string|null
     */
    #[Groups([
        'read:delivrable:collection',
        'read:delivrable:item',
        'read:homework:item'
        ])]
    private $fileUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups([
        'read:delivrable:collection',
        'read:delivrable:item',
        'read:homework:item'
        ])]
    private $filePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileSlug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

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

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

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

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(?\DateTimeInterface $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;

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
