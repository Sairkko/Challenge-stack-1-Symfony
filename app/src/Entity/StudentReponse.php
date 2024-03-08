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


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(type: Types::TEXT, length: 10, enumType: IsCorrectByTeacher::class)]
    private IsCorrectByTeacher $is_correct_by_teacher;

    #[ORM\ManyToOne(inversedBy: 'student_responses')]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'student_reponse')]
    private ?Question $id_question = null;

    public function __construct()
    {
        $this->is_correct_by_teacher = IsCorrectByTeacher::NULL;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getIdQuestion(): ?Question
    {
        return $this->id_question;
    }

    public function setIdQuestion(?Question $id_question): static
    {
        $this->id_question = $id_question;

        return $this;
    }
}
