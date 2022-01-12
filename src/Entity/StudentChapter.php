<?php

namespace App\Entity;

use App\Repository\StudentChapterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentChapterRepository::class)
 */
class StudentChapter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="studentChapters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourseChapter::class, inversedBy="studentChapters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapter;

    /**
     * @ORM\Column(type="integer")
     */
    private $pagesCompleted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

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

    public function getChapter(): ?InstructorCourseChapter
    {
        return $this->chapter;
    }

    public function setChapter(?InstructorCourseChapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function getPagesCompleted(): ?int
    {
        return $this->pagesCompleted;
    }

    public function setPagesCompleted(int $pagesCompleted): self
    {
        $this->pagesCompleted = $pagesCompleted;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getAllFields()
    {
        return get_object_vars($this);
    }
}
