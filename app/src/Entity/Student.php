<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    public function __toString(): string
    {
        // Supposons que chaque étudiant a un prénom (firstName) et un nom (lastName)
        return $this->name . ' ' . $this->last_name . " (" . $this->getIdUser()->getEmail().")";
    }


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last_name = null;

    #[ORM\OneToOne(mappedBy: 'id_student', cascade: ['persist', 'remove'])]
    private ?StudentReponse $studentReponse = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?Teacher $id_teacher = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $profil_picture = null;

    #[ORM\ManyToMany(targetEntity: StudentGroup::class, inversedBy: 'students')]
    private Collection $student_groupe;

    public function __construct()
    {
        $this->student_groupe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(User $id_user): static
    {
        $this->id_user = $id_user;

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

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getStudentReponse(): ?StudentReponse
    {
        return $this->studentReponse;
    }

    public function setStudentReponse(StudentReponse $studentReponse): static
    {
        // set the owning side of the relation if necessary
        if ($studentReponse->getIdStudent() !== $this) {
            $studentReponse->setIdStudent($this);
        }

        $this->studentReponse = $studentReponse;

        return $this;
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

    public function getProfilPicture(): ?string
    {
        return $this->profil_picture;
    }

    public function setProfilPicture(?string $profil_picture): static
    {
        $this->profil_picture = $profil_picture;

        return $this;
    }

    /**
     * @return Collection<int, StudentGroup>
     */
    public function getStudentGroupe(): Collection
    {
        return $this->student_groupe;
    }

    public function addStudentGroupe(StudentGroup $studentGroupe): static
    {
        if (!$this->student_groupe->contains($studentGroupe)) {
            $this->student_groupe->add($studentGroupe);
        }

        return $this;
    }

    public function removeStudentGroupe(StudentGroup $studentGroupe): static
    {
        $this->student_groupe->removeElement($studentGroupe);

        return $this;
    }
}
