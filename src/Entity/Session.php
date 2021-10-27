<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $course;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $theme;

    /**
     * @ORM\Column(type="smallint")
     */
    private $duration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bibliographyReferences;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $assignment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

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

    public function getBibliographyReferences(): ?string
    {
        return $this->bibliographyReferences;
    }

    public function setBibliographyReferences(?string $bibliographyReferences): self
    {
        $this->bibliographyReferences = $bibliographyReferences;

        return $this;
    }

    public function getAssignment(): ?string
    {
        return $this->assignment;
    }

    public function setAssignment(?string $assignment): self
    {
        $this->assignment = $assignment;

        return $this;
    }
}
