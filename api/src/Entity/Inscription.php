<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
#[ApiResource(
    mercure:true,
    normalizationContext:['groups' => ['read:inscription:collection']],
    itemOperations:[
        'put' => [
            'normalization_context' => ['groups' => [
                'put:inscription:item', 
            ]]
        ],
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:inscription:item', 
                ]]
        ]
    ]
)]
class Inscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'read:invitation:collection',
        'read:classroom:item',
        'read:inscription:item',
        'put:inscription:item',
        'read:classroom:collection'
        ])]
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Invitation::class, inversedBy="inscriptions")
     */
    private $invitation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    #[Groups([
        "read:invitation:collection",
        "read:inscription:item",
        "read:classroom:item",
        'read:classroom:collection'
        ])]
    private $accepted;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inscriptions")
     */
    #[Groups([
        'read:invitation:collection',
        'read:classroom:item',
        'read:inscription:item',
        'put:inscription:item',
        'read:classroom:collection'
        ])]
    private $userRegister;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTeacher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvitation(): ?Invitation
    {
        return $this->invitation;
    }

    public function setInvitation(?Invitation $invitation): self
    {
        $this->invitation = $invitation;

        return $this;
    }

    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(?bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }

    public function getUserRegister(): ?User
    {
        return $this->userRegister;
    }

    public function setUserRegister(?User $userRegister): self
    {
        $this->userRegister = $userRegister;

        return $this;
    }

    public function getIsTeacher(): ?bool
    {
        return $this->isTeacher;
    }

    public function setIsTeacher(?bool $isTeacher): self
    {
        $this->isTeacher = $isTeacher;

        return $this;
    }
}