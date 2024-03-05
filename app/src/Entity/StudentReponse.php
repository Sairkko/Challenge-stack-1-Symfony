<?php

namespace App\Entity;

use App\Enum\IsCorrectByTeacher;
use App\Repository\StudentReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentReponseRepository::class)]
class StudentReponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'studentReponse', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $id_student = null;

    #[ORM\OneToOne(inversedBy: 'studentReponse', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $id_question = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(type: Types::TEXT, length: 10, enumType: IsCorrectByTeacher::class)]
    private IsCorrectByTeacher $is_correct_by_teacher;

    public function __construct()
    {
        $this->is_correct_by_teacher = IsCorrectByTeacher::NULL;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdStudent(): ?Student
    {
        return $this->id_student;
    }

    public function setIdStudent(Student $id_student): static
    {
        $this->id_student = $id_student;

        return $this;
    }

    public function getIdQuestion(): ?Question
    {
        return $this->id_question;
    }

    public function setIdQuestion(Question $id_question): static
    {
        $this->id_question = $id_question;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getIsCorrectByTeacher(): IsCorrectByTeacher
    {
        return $this->is_correct_by_teacher;
    }

    public function setIsCorrectByTeacher(IsCorrectByTeacher $is_correct_by_teacher): static
    {
        $this->is_correct_by_teacher = $is_correct_by_teacher;

        return $this;
    }
}
