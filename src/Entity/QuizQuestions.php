<?php

namespace App\Entity;

use App\Repository\QuizQuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizQuestionsRepository::class)
 */
class QuizQuestions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="quizQuestions")
     */
    private $quiz;

    /**
     * @ORM\Column(type="text")
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answer;

    /**
     * @ORM\OneToMany(targetEntity=QuizChoices::class, mappedBy="question")
     */
    private $quizChoices;

    public function __construct()
    {
        $this->quizChoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

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
            $quizChoice->setQuestion($this);
        }

        return $this;
    }

    public function removeQuizChoice(QuizChoices $quizChoice): self
    {
        if ($this->quizChoices->removeElement($quizChoice)) {
            // set the owning side to null (unless already changed)
            if ($quizChoice->getQuestion() === $this) {
                $quizChoice->setQuestion(null);
            }
        }

        return $this;
    }
}
