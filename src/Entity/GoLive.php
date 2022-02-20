<?php

namespace App\Entity;

use App\Repository\GoLiveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GoLiveRepository::class)
 */
class GoLive
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourse::class, inversedBy="goLiveLinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instructorCourse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $startsAt;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getInstructorCourse(): ?InstructorCourse
    {
        return $this->instructorCourse;
    }

    public function setInstructorCourse(?InstructorCourse $instructorCourse): self
    {
        $this->instructorCourse = $instructorCourse;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getStartsAt(): ?string
    {
        return $this->startsAt;
    }

    public function setStartsAt(?string $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

 
}
