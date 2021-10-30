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
     * @ORM\ManyToOne(targetEntity=InstructorCourseChapter::class, inversedBy="quizzes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instructorCourseChapter;

    /**
     * @ORM\OneToMany(targetEntity=QuizQuestions::class, mappedBy="quiz")
     */
    private $quizQuestions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $activeQuestions;

    public function __construct()
    {
        $this->quizQuestions = new ArrayCollection();
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

    

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function __toString()
    {
        return $this->name;
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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getActiveQuestions(): ?int
    {
        return $this->activeQuestions;
    }

    public function setActiveQuestions(int $activeQuestions): self
    {
        $this->activeQuestions = $activeQuestions;

        return $this;
    }
}
