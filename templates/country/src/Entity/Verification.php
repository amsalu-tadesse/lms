<?php

namespace App\Entity;

use App\Repository\VerificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VerificationRepository::class)
 */
class Verification
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $verificationCode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $verificationExpiry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verificationCode;
    }

    public function setVerificationCode(string $verificationCode): self
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    public function getVerificationExpiry(): ?\DateTimeInterface
    {
        return $this->verificationExpiry;
    }

    public function setVerificationExpiry(\DateTimeInterface $verificationExpiry): self
    {
        $this->verificationExpiry = $verificationExpiry;

        return $this;
    }
}
