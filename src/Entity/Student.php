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
    private $userid;

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

    public function __construct()
    {
        $this->studentCourses = new ArrayCollection();
        $this->studentContentReactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(User $userid): self
    {
        $this->userid = $userid;

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
}
