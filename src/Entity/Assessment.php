<?php

namespace App\Entity;

use App\Repository\AssessmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssessmentRepository::class)
 */
class Assessment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="assessments")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $course;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $method;

    /**
     * @ORM\Column(type="smallint")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSupervised;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=LearningObjective::class, inversedBy="assessments")
     */
    private $learningObjectives;

    public function __construct()
    {
        $this->learningObjectives = new ArrayCollection();
    }

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

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

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

    public function getStage(): ?string
    {
        return $this->stage;
    }

    public function setStage(string $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function getIsSupervised(): ?bool
    {
        return $this->isSupervised;
    }

    public function setIsSupervised(bool $isSupervised): self
    {
        $this->isSupervised = $isSupervised;

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

    /**
     * @return Collection|LearningObjective[]
     */
    public function getLearningObjectives(): Collection
    {
        return $this->learningObjectives;
    }

    public function addLearningObjective(LearningObjective $learningObjective): self
    {
        if (!$this->learningObjectives->contains($learningObjective)) {
            $this->learningObjectives[] = $learningObjective;
        }

        return $this;
    }

    public function removeLearningObjective(LearningObjective $learningObjective): self
    {
        $this->learningObjectives->removeElement($learningObjective);

        return $this;
    }
}
