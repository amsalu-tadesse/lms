<?php

namespace App\Entity;

use App\Repository\QuestionAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionAnswerRepository::class)
 */
class QuestionAnswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="questionAnswers")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=Instructor::class, inversedBy="questionAnswers")
     */
    private $instructor;

    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private $question;


    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private $answer;

    /**
     * @ORM\OneToOne(targetEntity=QuestionAnswer::class, cascade={"persist", "remove"})
     */
    private $quationAnswer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourse::class, inversedBy="questionAnswers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $answeredAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoAnswer;

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

    public function getInstructor(): ?Instructor
    {
        return $this->instructor;
    }

    public function setInstructor(?Instructor $instructor): self
    {
        $this->instructor = $instructor;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getIsReply(): ?bool
    {
        return $this->isReply;
    }

    public function setIsReply(bool $isReply): self
    {
        $this->isReply = $isReply;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getQuationAnswer(): ?self
    {
        return $this->quationAnswer;
    }

    public function setQuationAnswer(?self $quationAnswer): self
    {
        $this->quationAnswer = $quationAnswer;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCourse(): ?InstructorCourse
    {
        return $this->course;
    }

    public function setCourse(?InstructorCourse $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getAnsweredAt(): ?\DateTimeInterface
    {
        return $this->answeredAt;
    }

    public function setAnsweredAt(?\DateTimeInterface $answeredAt): self
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }

    public function getNotification(): ?bool
    {
        return $this->notification;
    }

    public function setNotification(bool $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function getVideoAnswer(): ?string
    {
        return $this->videoAnswer;
    }

    public function setVideoAnswer(?string $videoAnswer): self
    {
        $this->videoAnswer = $videoAnswer;

        return $this;
    }
}
