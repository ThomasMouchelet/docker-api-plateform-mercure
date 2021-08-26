<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InvitationRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=InvitationRepository::class)
 */
#[ApiResource(
    mercure:true,
    normalizationContext:['groups' => ['read:invitation:collection']],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => [
                'read:invitation:item', 
                ]]
        ]
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties: [
        "owner","classroom","uuid" => "exact"
        ]
)]
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        "read:invitation:collection",
        "read:invitation:item"],
        "read:classroom:collection"
        )]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read:invitation:collection","read:invitation:item"])]
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitations")
     */
    #[Groups(["read:invitation:collection","read:invitation:item"])]
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Classroom::class, inversedBy="invitations")
     */
    #[Groups([
        'read:invitation:collection',
        'read:invitation:item'
        ])]
    private $classroom;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="invitation")
     */
    #[Groups([
        "read:invitation:collection",
        "read:invitation:item",
        "read:classroom:item",
        "read:classroom:collection"
        ])]
    private $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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
            $inscription->setInvitation($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getInvitation() === $this) {
                $inscription->setInvitation(null);
            }
        }

        return $this;
    }
}