<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $instruction;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percentage;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $passvalue;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourseChapter::class, inversedBy="quizzes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instructorCourseChapter;

    /**
     * @ORM\OneToMany(targetEntity=QuizQuestions::class, mappedBy="quiz")
     */
    private $quizQuestions;

    /**
     * @ORM\OneToMany(targetEntity=QuizChoices::class, mappedBy="quiz")
     */
    private $quizChoices;

    public function __construct()
    {
        $this->quizQuestions = new ArrayCollection();
        $this->quizChoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(?float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getPassvalue(): ?float
    {
        return $this->passvalue;
    }

    public function setPassvalue(?float $passvalue): self
    {
        $this->passvalue = $passvalue;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(?int $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getInstructorCourseChapter(): ?InstructorCourseChapter
    {
        return $this->instructorCourseChapter;
    }

    public function setInstructorCourseChapter(?InstructorCourseChapter $instructorCourseChapter): self
    {
        $this->instructorCourseChapter = $instructorCourseChapter;

        return $this;
    }

    /**
     * @return Collection|QuizQuestions[]
     */
    public function getQuizQuestions(): Collection
    {
        return $this->quizQuestions;
    }

    public function addQuizQuestion(QuizQuestions $quizQuestion): self
    {
        if (!$this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions[] = $quizQuestion;
            $quizQuestion->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizQuestion(QuizQuestions $quizQuestion): self
    {
        if ($this->quizQuestions->removeElement($quizQuestion)) {
            // set the owning side to null (unless already changed)
            if ($quizQuestion->getQuiz() === $this) {
                $quizQuestion->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuizChoices[]
     */
    public function getQuizChoices(): Collection
    {
        return $this->quizChoices;
    }

    public function addQuizChoice(QuizChoices $quizChoice): self
    {
        if (!$this->quizChoices->contains($quizChoice)) {
            $this->quizChoices[] = $quizChoice;
            $quizChoice->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizChoice(QuizChoices $quizChoice): self
    {
        if ($this->quizChoices->removeElement($quizChoice)) {
            // set the owning side to null (unless already changed)
            if ($quizChoice->getQuiz() === $this) {
                $quizChoice->setQuiz(null);
            }
        }

        return $this;
    }
}
