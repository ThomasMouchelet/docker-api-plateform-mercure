<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\UploadFileController;
use App\Repository\UploadFileRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

/**
 * @ORM\Entity(repositoryClass=UploadFileRepository::class)
 * @Vich\Uploadable()
 */
#[ApiResource(
    mercure:true,
    normalizationContext:['groups' => ['read:uploadFile:collection']],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:uploadFile:item', 
                ]]
            ]
    ],
    collectionOperations: [
        'get',
        'delivrale' => [
            'method' => 'post',
            'path' => '/upload_files',
            'deserialize' => false,
            'controller' => UploadFileController::class,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => 'string',
                                    'format' => 'binary'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
)]
class UploadFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'read:uploadFile:collection',
        'read:uploadFile:item',
        ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups([
        'read:uploadFile:collection',
        'read:uploadFile:item',
        ])]
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups([
        'read:uploadFile:collection',
        'read:uploadFile:item',
        ])]
    private $path;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="delivrable_file", fileNameProperty="path")
     */
    private $file;

    /**
     * @var string|null
     */
    #[Groups([
        'read:uploadFile:collection',
        'read:uploadFile:item',
        ])]
    private $url;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups([
        'read:uploadFile:collection',
        'read:uploadFile:item',
        ])]
    private $uploadedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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
}
