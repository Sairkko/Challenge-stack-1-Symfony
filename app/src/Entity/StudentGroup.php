<?php

namespace App\Entity;

use App\Repository\StudentGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentGroupRepository::class)]
class StudentGroup
{
    public function __toString(): string
    {
        return $this->name;
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: School::class, inversedBy: 'studentGroups')]
    private Collection $id_school;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Student::class, mappedBy: 'student_groupe')]
    private Collection $students;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'groups')]
    private Collection $events;

    public function __construct()
    {
        $this->id_school = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, School>
     */
    public function getIdSchool(): Collection
    {
        return $this->id_school;
    }

    public function addIdSchool(School $idSchool): static
    {
        if (!$this->id_school->contains($idSchool)) {
            $this->id_school->add($idSchool);
        }

        return $this;
    }

    public function removeIdSchool(School $idSchool): static
    {
        $this->id_school->removeElement($idSchool);

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
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->addStudentGroupe($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            $student->removeStudentGroupe($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addGroup($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeGroup($this);
        }

        return $this;
    }


}
