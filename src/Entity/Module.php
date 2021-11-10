<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $leader;

    /**
     * @ORM\Column(type="smallint")
     */
    private $ects;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="module")
     * @ORM\OrderBy({"lectureHours" = "DESC", "name" = "ASC"})
     */
    private $courses;

    /**
     * @ORM\ManyToOne(targetEntity=Semester::class, inversedBy="modules")
     * @ORM\JoinColumn(nullable=true)
     */
    private $semester;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subDepartment;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prerequiste;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $assessmentMidHours;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $assessmentFinalHours;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $syncElearningHours;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $asyncElearningHours;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $selfStudyHours;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $projecthours;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $otherHours;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
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

    public function getLeader(): ?User
    {
        return $this->leader;
    }

    public function setLeader(?User $leader): self
    {
        $this->leader = $leader;

        return $this;
    }

    public function getEcts(): ?int
    {
        return $this->ects;
    }

    public function setEcts(int $ects): self
    {
        $this->ects = $ects;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setModule($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getModule() === $this) {
                $course->setModule(null);
            }
        }

        return $this;
    }

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): self
    {
        $this->semester = $semester;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getSubDepartment(): ?string
    {
        return $this->subDepartment;
    }

    public function setSubDepartment(?string $subDepartment): self
    {
        $this->subDepartment = $subDepartment;

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

    public function getPrerequiste(): ?string
    {
        return $this->prerequiste;
    }

    public function setPrerequiste(?string $prerequiste): self
    {
        $this->prerequiste = $prerequiste;

        return $this;
    }

    public function getAssessmentMidHours(): ?float
    {
        return $this->assessmentMidHours;
    }

    public function setAssessmentMidHours(?float $assessmentMidHours): self
    {
        $this->assessmentMidHours = $assessmentMidHours;

        return $this;
    }

    public function getAssessmentFinalHours(): ?float
    {
        return $this->assessmentFinalHours;
    }

    public function setAssessmentFinalHours(?float $assessmentFinalHours): self
    {
        $this->assessmentFinalHours = $assessmentFinalHours;

        return $this;
    }

    public function getSyncElearningHours(): ?float
    {
        return $this->syncElearningHours;
    }

    public function setSyncElearningHours(?float $syncElearningHours): self
    {
        $this->syncElearningHours = $syncElearningHours;

        return $this;
    }

    public function getAsyncElearningHours(): ?float
    {
        return $this->asyncElearningHours;
    }

    public function setAsyncElearningHours(?float $asyncElearningHours): self
    {
        $this->asyncElearningHours = $asyncElearningHours;

        return $this;
    }

    public function getSelfStudyHours(): ?float
    {
        return $this->selfStudyHours;
    }

    public function setSelfStudyHours(?float $selfStudyHours): self
    {
        $this->selfStudyHours = $selfStudyHours;

        return $this;
    }

    public function getProjecthours(): ?float
    {
        return $this->projecthours;
    }

    public function setProjecthours(?float $projecthours): self
    {
        $this->projecthours = $projecthours;

        return $this;
    }

    public function getOtherHours(): ?float
    {
        return $this->otherHours;
    }

    public function setOtherHours(?float $otherHours): self
    {
        $this->otherHours = $otherHours;

        return $this;
    }
}
