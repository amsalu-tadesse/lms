<?php

namespace App\Entity;

use App\Repository\StudentCourseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentCourseRepository::class)
 */
class StudentCourse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="studentCourses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourse::class, inversedBy="studentCourses")
     */
    private $instructorCourse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $isAtPage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $completedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $teacherNotification;

    /**
     * @ORM\Column(type="boolean")
     */
    private $directorNotification;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $qrCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $qrUrl;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

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

    public function getIsAtPage(): ?int
    {
        return $this->isAtPage;
    }

    public function setIsAtPage(?int $isAtPage): self
    {
        $this->isAtPage = $isAtPage;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeInterface $completedAt): self
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getTeacherNotification(): ?bool
    {
        return $this->teacherNotification;
    }

    public function setTeacherNotification(bool $teacherNotification): self
    {
        $this->teacherNotification = $teacherNotification;

        return $this;
    }

    public function getDirectorNotification(): ?bool
    {
        return $this->directorNotification;
    }

    public function setDirectorNotification(bool $directorNotification): self
    {
        $this->directorNotification = $directorNotification;

        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): self
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getQrUrl(): ?string
    {
        return $this->qrUrl;
    }

    public function setQrUrl(?string $qrUrl): self
    {
        $this->qrUrl = $qrUrl;

        return $this;
    }
}
