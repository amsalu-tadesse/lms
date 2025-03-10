<?php

namespace App\Entity;

use App\Repository\ContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContentRepository::class)
 */
class Content
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $filename;


    /**
     * @ORM\OneToMany(targetEntity=StudentContentReaction::class, mappedBy="content")
     */
    private $studentContentReactions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoLink;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorCourseChapter::class, inversedBy="contents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapter;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resource;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $resourceNames;

    public function __construct()
    {
        $this->studentContentReactions = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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
            $studentContentReaction->setContent($this);
        }

        return $this;
    }

    public function removeStudentContentReaction(StudentContentReaction $studentContentReaction): self
    {
        if ($this->studentContentReactions->removeElement($studentContentReaction)) {
            // set the owning side to null (unless already changed)
            if ($studentContentReaction->getContent() === $this) {
                $studentContentReaction->setContent(null);
            }
        }

        return $this;
    }

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(?string $videoLink): self
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    public function getChapter(): ?InstructorCourseChapter
    {
        return $this->chapter;
    }

    public function setChapter(?InstructorCourseChapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getResourceNames(): ?string
    {
        return $this->resourceNames;
    }

    public function setResourceNames(string $resourceNames): self
    {
        $this->resourceNames = $resourceNames;

        return $this;
    }

    public function getAllFields()
    {
        return get_object_vars($this);
    }
}
