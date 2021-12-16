<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=AcademicLevel::class, inversedBy="students")
     */
    private $academicLevel;

    /**
     * @ORM\OneToMany(targetEntity=StudentCourse::class, mappedBy="student")
     */
    private $studentCourses;

    /**
     * @ORM\OneToMany(targetEntity=StudentContentReaction::class, mappedBy="student")
     */
    private $studentContentReactions;

    /**
     * @ORM\OneToMany(targetEntity=StudentChapter::class, mappedBy="student", orphanRemoval=true)
     */
    private $studentChapters;

    /**
     * @ORM\OneToMany(targetEntity=StudentQuestion::class, mappedBy="student")
     */
    private $studentQuestions;

    /**
     * @ORM\OneToMany(targetEntity=StudentQuiz::class, mappedBy="student")
     */
    private $studentQuizzes;

    /**
     * @ORM\OneToMany(targetEntity=QuestionAnswer::class, mappedBy="student")
     */
    private $questionAnswers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $student_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilePicture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $profileUpdated;

    public function __construct()
    {
        $this->studentCourses = new ArrayCollection();
        $this->studentContentReactions = new ArrayCollection();
        $this->studentChapters = new ArrayCollection();
        $this->studentQuestions = new ArrayCollection();
        $this->studentQuizzes = new ArrayCollection();
        $this->questionAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAcademicLevel(): ?AcademicLevel
    {
        return $this->academicLevel;
    }

    public function setAcademicLevel(?AcademicLevel $academicLevel): self
    {
        $this->academicLevel = $academicLevel;

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
            $studentCourse->setStudent($this);
        }

        return $this;
    }

    public function removeStudentCourse(StudentCourse $studentCourse): self
    {
        if ($this->studentCourses->removeElement($studentCourse)) {
            // set the owning side to null (unless already changed)
            if ($studentCourse->getStudent() === $this) {
                $studentCourse->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StudentContentReaction[]
     */
    public function getStudentContentReactions(): Collection
    {
        return $this->studentContentReactions;
    }

    public function addStudentContentReaction(StudentContentReaction $studentContentReaction): self
    {
        if (!$this->studentContentReactions->contains($studentContentReaction)) {
            $this->studentContentReactions[] = $studentContentReaction;
            $studentContentReaction->setStudent($this);
        }

        return $this;
    }

    public function removeStudentContentReaction(StudentContentReaction $studentContentReaction): self
    {
        if ($this->studentContentReactions->removeElement($studentContentReaction)) {
            // set the owning side to null (unless already changed)
            if ($studentContentReaction->getStudent() === $this) {
                $studentContentReaction->setStudent(null);
            }
        }

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
            $studentChapter->setStudent($this);
        }

        return $this;
    }

    public function removeStudentChapter(StudentChapter $studentChapter): self
    {
        if ($this->studentChapters->removeElement($studentChapter)) {
            // set the owning side to null (unless already changed)
            if ($studentChapter->getStudent() === $this) {
                $studentChapter->setStudent(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        // return $this->getUser();
        return $this->user->getFirstName()." ".$this->user->getMiddleName()." ".$this->user->getLastName();
    }

    /**
     * @return Collection|StudentQuestion[]
     */
    public function getStudentQuestions(): Collection
    {
        return $this->studentQuestions;
    }

    public function addStudentQuestion(StudentQuestion $studentQuestion): self
    {
        if (!$this->studentQuestions->contains($studentQuestion)) {
            $this->studentQuestions[] = $studentQuestion;
            $studentQuestion->setStudent($this);
        }

        return $this;
    }

    public function removeStudentQuestion(StudentQuestion $studentQuestion): self
    {
        if ($this->studentQuestions->removeElement($studentQuestion)) {
            // set the owning side to null (unless already changed)
            if ($studentQuestion->getStudent() === $this) {
                $studentQuestion->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StudentQuiz[]
     */
    public function getStudentQuizzes(): Collection
    {
        return $this->studentQuizzes;
    }

    public function addStudentQuiz(StudentQuiz $studentQuiz): self
    {
        if (!$this->studentQuizzes->contains($studentQuiz)) {
            $this->studentQuizzes[] = $studentQuiz;
            $studentQuiz->setStudent($this);
        }

        return $this;
    }

    public function removeStudentQuiz(StudentQuiz $studentQuiz): self
    {
        if ($this->studentQuizzes->removeElement($studentQuiz)) {
            // set the owning side to null (unless already changed)
            if ($studentQuiz->getStudent() === $this) {
                $studentQuiz->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuestionAnswer[]
     */
    public function getQuestionAnswers(): Collection
    {
        return $this->questionAnswers;
    }

    public function addQuestionAnswer(QuestionAnswer $questionAnswer): self
    {
        if (!$this->questionAnswers->contains($questionAnswer)) {
            $this->questionAnswers[] = $questionAnswer;
            $questionAnswer->setStudent($this);
        }

        return $this;
    }

    public function removeQuestionAnswer(QuestionAnswer $questionAnswer): self
    {
        if ($this->questionAnswers->removeElement($questionAnswer)) {
            // set the owning side to null (unless already changed)
            if ($questionAnswer->getStudent() === $this) {
                $questionAnswer->setStudent(null);
            }
        }

        return $this;
    }

    public function getStudentId(): ?string
    {
        return $this->student_id;
    }

    public function setStudentId(string $student_id): self
    {
        $this->student_id = $student_id;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getProfileUpdated(): ?bool
    {
        return $this->profileUpdated;
    }

    public function setProfileUpdated(bool $profileUpdated): self
    {
        $this->profileUpdated = $profileUpdated;

        return $this;
    }
}
