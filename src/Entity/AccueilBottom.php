<?php

namespace App\Entity;

use App\Repository\AccueilBottomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccueilBottomRepository::class)]
class AccueilBottom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $Title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Text = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $Link = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(?string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->Link;
    }

    public function setLink(?string $Link): self
    {
        $this->Link = $Link;

        return $this;
    }
}
