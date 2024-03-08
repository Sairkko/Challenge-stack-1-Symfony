<?php

namespace App\Entity;

use App\Enum\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    public function __toString(): string
    {
        return $this->question_text;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Test $id_test = null;

    #[ORM\Column(type: Types::TEXT, length: 10, enumType: QuestionType::class)]
    private QuestionType $type;

    #[ORM\Column(length: 255)]
    private ?string $question_text = null;

    #[ORM\Column(nullable: true)]
    private ?int $point = null;

    #[ORM\OneToMany(mappedBy: 'id_question', targetEntity: QuestionReponse::class)]
    private Collection $questionReponses;

    #[ORM\OneToMany(mappedBy: 'id_question', targetEntity: StudentReponse::class)]
    private Collection $student_reponse;

    public function __construct()
    {
        $this->questionReponses = new ArrayCollection();
        $this->type = QuestionType::QCM;
        $this->student_reponse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTest(): ?Test
    {
        return $this->id_test;
    }

    public function setIdTest(?Test $id_test): static
    {
        $this->id_test = $id_test;

        return $this;
    }

    public function getType(): QuestionType
    {
        return $this->type;
    }

    public function setType(QuestionType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getQuestionText(): ?string
    {
        return $this->question_text;
    }

    public function setQuestionText(string $question_text): static
    {
        $this->question_text = $question_text;

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): static
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return Collection<int, QuestionReponse>
     */
    public function getQuestionReponses(): Collection
    {
        return $this->questionReponses;
    }

    public function addQuestionReponse(QuestionReponse $questionReponse): static
    {
        if (!$this->questionReponses->contains($questionReponse)) {
            $this->questionReponses->add($questionReponse);
            $questionReponse->setIdQuestion($this);
        }

        return $this;
    }

    public function removeQuestionReponse(QuestionReponse $questionReponse): static
    {
        if ($this->questionReponses->removeElement($questionReponse)) {
            // set the owning side to null (unless already changed)
            if ($questionReponse->getIdQuestion() === $this) {
                $questionReponse->setIdQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StudentReponse>
     */
    public function getStudentReponse(): Collection
    {
        return $this->student_reponse;
    }

    public function addStudentReponse(StudentReponse $studentReponse): static
    {
        if (!$this->student_reponse->contains($studentReponse)) {
            $this->student_reponse->add($studentReponse);
            $studentReponse->setIdQuestion($this);
        }

        return $this;
    }

    public function removeStudentReponse(StudentReponse $studentReponse): static
    {
        if ($this->student_reponse->removeElement($studentReponse)) {
            // set the owning side to null (unless already changed)
            if ($studentReponse->getIdQuestion() === $this) {
                $studentReponse->setIdQuestion(null);
            }
        }

        return $this;
    }
}
