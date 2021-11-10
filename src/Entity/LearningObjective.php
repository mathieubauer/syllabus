<?php

namespace App\Entity;

use App\Repository\LearningObjectiveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LearningObjectiveRepository::class)
 */
class LearningObjective
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
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Assessment::class, mappedBy="learningObjectives")
     */
    private $assessments;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $displayOrder;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTitle;

    public function __construct()
    {
        $this->assessments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
     * @return Collection|Assessment[]
     */
    public function getAssessments(): Collection
    {
        return $this->assessments;
    }

    public function addAssessment(Assessment $assessment): self
    {
        if (!$this->assessments->contains($assessment)) {
            $this->assessments[] = $assessment;
            $assessment->addLearningObjective($this);
        }

        return $this;
    }

    public function removeAssessment(Assessment $assessment): self
    {
        if ($this->assessments->removeElement($assessment)) {
            $assessment->removeLearningObjective($this);
        }

        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $displayOrder): self
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    public function getIsTitle(): ?bool
    {
        return $this->isTitle;
    }

    public function setIsTitle(?bool $isTitle): self
    {
        $this->isTitle = $isTitle;

        return $this;
    }
}
