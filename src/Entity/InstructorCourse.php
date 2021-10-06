<?php

namespace App\Entity;

use App\Repository\InstructorCourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstructorCourseRepository::class)
 */
class InstructorCourse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="instructorCourses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

 

    /**
     * @ORM\ManyToOne(targetEntity=Instructor::class, inversedBy="instructorCourses")
     */
    private $instructor;

    /**
     * @ORM\OneToMany(targetEntity=StudentCourse::class, mappedBy="instructorCourse")
     */
    private $studentCourses;

    /**
     * @ORM\OneToMany(targetEntity=Content::class, mappedBy="instructorCourse")
     */
    private $contents;

    /**
     * @ORM\OneToMany(targetEntity=Exam::class, mappedBy="instructorCourse")
     */
    private $exams;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourseStatus::class)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=InstructorCourseChapter::class, mappedBy="instructorCourse", orphanRemoval=true)
     */
    private $instructorCourseChapters;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    public function __construct()
    {
        $this->studentCourses = new ArrayCollection();
        $this->contents = new ArrayCollection();
        $this->exams = new ArrayCollection();
        $this->instructorCourseChapters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

 

    public function getInstructor(): ?Instructor
    {
        return $this->instructor;
    }

    public function setInstructor(?Instructor $instructor): self
    {
        $this->instructor = $instructor;

        return $this;
    }

    /**
     * @return Collection|StudentCourse[]
     */
    public function getStudentCourses(): Collection
    {
        return $this->studentCourses;
    }

    public function addStudentCourse(StudentCourse $studentCourse): self
    {
        if (!$this->studentCourses->contains($studentCourse)) {
            $this->studentCourses[] = $studentCourse;
            $studentCourse->setInstructorCourse($this);
        }

        return $this;
    }

    public function removeStudentCourse(StudentCourse $studentCourse): self
    {
        if ($this->studentCourses->removeElement($studentCourse)) {
            // set the owning side to null (unless already changed)
            if ($studentCourse->getInstructorCourse() === $this) {
                $studentCourse->setInstructorCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Content[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(Content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setInstructorCourse($this);
        }

        return $this;
    }

    public function removeContent(Content $content): self
    {
        if ($this->contents->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getInstructorCourse() === $this) {
                $content->setInstructorCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exam[]
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): self
    {
        if (!$this->exams->contains($exam)) {
            $this->exams[] = $exam;
            $exam->setInstructorCourse($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): self
    {
        if ($this->exams->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getInstructorCourse() === $this) {
                $exam->setInstructorCourse(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?InstructorCourseStatus
    {
        return $this->status;
    }

    public function setStatus(?InstructorCourseStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|InstructorCourseChapter[]
     */
    public function getInstructorCourseChapters(): Collection
    {
        return $this->instructorCourseChapters;
    }

    public function addInstructorCourseChapter(InstructorCourseChapter $instructorCourseChapter): self
    {
        if (!$this->instructorCourseChapters->contains($instructorCourseChapter)) {
            $this->instructorCourseChapters[] = $instructorCourseChapter;
            $instructorCourseChapter->setInstructorCourse($this);
        }

        return $this;
    }

    public function removeInstructorCourseChapter(InstructorCourseChapter $instructorCourseChapter): self
    {
        if ($this->instructorCourseChapters->removeElement($instructorCourseChapter)) {
            // set the owning side to null (unless already changed)
            if ($instructorCourseChapter->getInstructorCourse() === $this) {
                $instructorCourseChapter->setInstructorCourse(null);
            }
        }

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
/*public function __toString()
{
    return $this->course->getName();
}*/
}
