<?php

namespace App\Entity;

use App\Repository\InstructorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstructorRepository::class)
 */
class Instructor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="instructors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=AcademicLevel::class, inversedBy="instructors")
     */
    private $academicLevel;

    /**
     * @ORM\OneToMany(targetEntity=InstructorCourse::class, mappedBy="instructor")
     */
    private $instructorCourses;

    public function __construct()
    {
        $this->instructorCourses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
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
     * @return Collection|InstructorCourse[]
     */
    public function getInstructorCourses(): Collection
    {
        return $this->instructorCourses;
    }

    public function addInstructorCourse(InstructorCourse $instructorCourse): self
    {
        if (!$this->instructorCourses->contains($instructorCourse)) {
            $this->instructorCourses[] = $instructorCourse;
            $instructorCourse->setInstructor($this);
        }

        return $this;
    }

    public function removeInstructorCourse(InstructorCourse $instructorCourse): self
    {
        if ($this->instructorCourses->removeElement($instructorCourse)) {
            // set the owning side to null (unless already changed)
            if ($instructorCourse->getInstructor() === $this) {
                $instructorCourse->setInstructor(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->user->getFirstName() . " ".$this->user->getLastName();
    }

   
}
