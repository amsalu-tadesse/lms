<?php

namespace App\Entity;

use App\Repository\ContentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContentRepository::class)
 */
class Content
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $chapter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isrenderable;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourse::class, inversedBy="contents")
     */
    private $instructorCourse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChapter(): ?string
    {
        return $this->chapter;
    }

    public function setChapter(string $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFile(): ?int
    {
        return $this->file;
    }

    public function setFile(?int $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getIsrenderable(): ?bool
    {
        return $this->isrenderable;
    }

    public function setIsrenderable(bool $isrenderable): self
    {
        $this->isrenderable = $isrenderable;

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
}
