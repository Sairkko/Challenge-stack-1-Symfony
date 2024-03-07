<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $id_teacher = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'id_module', targetEntity: Lesson::class)]
    private Collection $lessons;

    #[ORM\ManyToMany(targetEntity: StudentGroup::class, mappedBy: 'modules')]
    private Collection $studentGroups;

    #[ORM\ManyToMany(targetEntity: Test::class, mappedBy: 'modules')]
    private Collection $tests;

    public function __toString(): string
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
        $this->studentGroups = new ArrayCollection();
        $this->tests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTeacher(): ?Teacher
    {
        return $this->id_teacher;
    }

    public function setIdTeacher(?Teacher $id_teacher): static
    {
        $this->id_teacher = $id_teacher;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Lesson>
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lesson $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
            $lesson->setIdModule($this);
        }

        return $this;
    }

    public function removeLesson(Lesson $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getIdModule() === $this) {
                $lesson->setIdModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StudentGroup>
     */
    public function getStudentGroups(): Collection
    {
        return $this->studentGroups;
    }

    public function addStudentGroup(StudentGroup $studentGroup): static
    {
        if (!$this->studentGroups->contains($studentGroup)) {
            $this->studentGroups->add($studentGroup);
            $studentGroup->addModule($this);
        }

        return $this;
    }

    public function removeStudentGroup(StudentGroup $studentGroup): static
    {
        if ($this->studentGroups->removeElement($studentGroup)) {
            $studentGroup->removeModule($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Test>
     */
    public function getTests(): Collection
    {
        return $this->tests;
    }

    public function addTest(Test $test): static
    {
        if (!$this->tests->contains($test)) {
            $this->tests->add($test);
            $test->addModule($this);
        }

        return $this;
    }

    public function removeTest(Test $test): static
    {
        if ($this->tests->removeElement($test)) {
            $test->removeModule($this);
        }

        return $this;
    }
}
