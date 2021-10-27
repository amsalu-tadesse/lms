<?php

namespace App\Entity;

use App\Repository\InstructorCourseChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstructorCourseChapterRepository::class)
 */
class InstructorCourseChapter
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
    private $chapter;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourse::class, inversedBy="instructorCourseChapters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instructorCourse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Content::class, mappedBy="chapter", orphanRemoval=true)
     */
    private $contents;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $topic;

    /**
     * @ORM\OneToMany(targetEntity=StudentChapter::class, mappedBy="chapter", orphanRemoval=true)
     */
    private $studentChapters;

    /**
     * @ORM\OneToMany(targetEntity=Quiz::class, mappedBy="instructorCourseChapter")
     */
    private $quizzes;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
        $this->studentChapters = new ArrayCollection();
        $this->quizzes = new ArrayCollection();
    }

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

    public function getInstructorCourse(): ?InstructorCourse
    {
        return $this->instructorCourse;
    }

    public function setInstructorCourse(?InstructorCourse $instructorCourse): self
    {
        $this->instructorCourse = $instructorCourse;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

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
            $content->setChapter($this);
        }

        return $this;
    }

    public function removeContent(Content $content): self
    {
        if ($this->contents->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getChapter() === $this) {
                $content->setChapter(null);
            }
        }

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return Collection|StudentChapter[]
     */
    public function getStudentChapters(): Collection
    {
        return $this->studentChapters;
    }

    public function addStudentChapter(StudentChapter $studentChapter): self
    {
        if (!$this->studentChapters->contains($studentChapter)) {
            $this->studentChapters[] = $studentChapter;
            $studentChapter->setChapter($this);
        }

        return $this;
    }

    public function removeStudentChapter(StudentChapter $studentChapter): self
    {
        if ($this->studentChapters->removeElement($studentChapter)) {
            // set the owning side to null (unless already changed)
            if ($studentChapter->getChapter() === $this) {
                $studentChapter->setChapter(null);
            }
        }

        return $this;
    }

 

    /**
     * @return Collection|Quiz[]
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes[] = $quiz;
            $quiz->setInstructorCourseChapter($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getInstructorCourseChapter() === $this) {
                $quiz->setInstructorCourseChapter(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->topic;
    }
}
