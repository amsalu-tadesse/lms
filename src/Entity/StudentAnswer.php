<?php

namespace App\Entity;

use App\Repository\StudentAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentAnswerRepository::class)
 */
class StudentAnswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAllFields()
    {
        return get_object_vars($this);
    }
}
