<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

use App\Events\EventRepositoryImpl; 

#[ORM\Entity(repositoryClass: EventRepositoryImpl::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?Uuid $user_id = null;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?Uuid $work_entry_id = null;

    #[ORM\Column(length: 255)]
    private ?string $action = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?Uuid
    {
        return $this->user_id;
    }

    public function setUserId(?Uuid $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getWorkEntryId(): ?Uuid
    {
        return $this->work_entry_id;
    }

    public function setWorkEntryId(?Uuid $work_entry_id): static
    {
        $this->work_entry_id = $work_entry_id;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
