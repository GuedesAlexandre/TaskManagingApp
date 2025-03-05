<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['task:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['task:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['task:read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['task:read'])]
    private ?bool $status = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['task:read'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->created_at === null) {
            $dateTime = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $this->created_at = $dateTime;
        }
    }

  public function getCreatedAtValue(): ?string
  {
    return $this->created_at->format("Y-m-d H:i:s");
  }
  public function setDescription(string $description): void
  {
    $this->description = $description;

  }
  public function getDescription(): ?string
  {
    return $this->description;

  }
  
public function getId(): ?int
{
    return $this->id;
}

public function getTitle(): ?string
{
    return $this->title;
}

public function setTitle(string $title): self
{
    $this->title = $title;
    return $this;
}

public function getStatus(): ?bool
{
    return $this->status;
}

public function setStatus(bool $status): self
{
    $this->status = $status;
    return $this;
}

public function getCreatedAt(): ?\DateTimeInterface
{
    return $this->created_at;
}

public function setCreatedAt(\DateTimeInterface $created_at): self
{
    $this->created_at = $created_at;
    return $this;
}

}