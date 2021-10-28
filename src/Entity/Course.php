<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $credit;

    /**
     * @ORM\ManyToOne(targetEntity=CourseCategory::class, inversedBy="courses")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=InstructorCourse::class, mappedBy="course")
     */
    private $instructorCourses;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function __construct()
    {
        $this->instructorCourses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function setCredit(?string $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function getCategory(): ?CourseCategory
    {
        return $this->category;
    }

    public function setCategory(?CourseCategory $category): self
    {
        $this->category = $category;

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
            $instructorCourse->setCourse($this);
        }

        return $this;
    }

    public function removeInstructorCourse(InstructorCourse $instructorCourse): self
    {
        if ($this->instructorCourses->removeElement($instructorCourse)) {
            // set the owning side to null (unless already changed)
            if ($instructorCourse->getCourse() === $this) {
                $instructorCourse->setCourse(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    
}
