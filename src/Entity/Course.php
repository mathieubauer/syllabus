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
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="courses")
     */
    private $teachingProfessor;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $module;

    /**
     * @ORM\Column(type="float")
     */
    private $lectureHours;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $learningOutcomes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $eLearning;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textbook;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bibliography;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $linksWithCompanies;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $teachingMethods;

    /**
     * @ORM\OneToMany(targetEntity=Session::class, mappedBy="course")
     */
    private $sessions;

    /**
     * @ORM\OneToMany(targetEntity=Assessment::class, mappedBy="course")
     */
    private $assessments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $language;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->teachingProfessor = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->assessments = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getTeachingProfessor(): Collection
    {
        return $this->teachingProfessor;
    }

    public function addTeachingProfessor(User $teachingProfessor): self
    {
        if (!$this->teachingProfessor->contains($teachingProfessor)) {
            $this->teachingProfessor[] = $teachingProfessor;
        }

        return $this;
    }

    public function removeTeachingProfessor(User $teachingProfessor): self
    {
        $this->teachingProfessor->removeElement($teachingProfessor);

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getLectureHours(): ?float
    {
        return $this->lectureHours;
    }

    public function setLectureHours(float $lectureHours): self
    {
        $this->lectureHours = $lectureHours;

        return $this;
    }

    public function getLearningOutcomes(): ?string
    {
        return $this->learningOutcomes;
    }

    public function setLearningOutcomes(?string $learningOutcomes): self
    {
        $this->learningOutcomes = $learningOutcomes;

        return $this;
    }

    public function getELearning(): ?string
    {
        return $this->eLearning;
    }

    public function setELearning(?string $eLearning): self
    {
        $this->eLearning = $eLearning;

        return $this;
    }

    public function getTextbook(): ?string
    {
        return $this->textbook;
    }

    public function setTextbook(?string $textbook): self
    {
        $this->textbook = $textbook;

        return $this;
    }

    public function getBibliography(): ?string
    {
        return $this->bibliography;
    }

    public function setBibliography(?string $bibliography): self
    {
        $this->bibliography = $bibliography;

        return $this;
    }

    public function getLinksWithCompanies(): ?string
    {
        return $this->linksWithCompanies;
    }

    public function setLinksWithCompanies(?string $linksWithCompanies): self
    {
        $this->linksWithCompanies = $linksWithCompanies;

        return $this;
    }

    public function getTeachingMethods(): ?string
    {
        return $this->teachingMethods;
    }

    public function setTeachingMethods(?string $teachingMethods): self
    {
        $this->teachingMethods = $teachingMethods;

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setCourse($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getCourse() === $this) {
                $session->setCourse(null);
            }
        }

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
            $assessment->setCourse($this);
        }

        return $this;
    }

    public function removeAssessment(Assessment $assessment): self
    {
        if ($this->assessments->removeElement($assessment)) {
            // set the owning side to null (unless already changed)
            if ($assessment->getCourse() === $this) {
                $assessment->setCourse(null);
            }
        }

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

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
}
