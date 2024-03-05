<?php

namespace App\Enum;

enum QuestionType: string
{
    case QCM = 'qcm';
    case UNIQUE_CHOICE = 'unique_choice';
    case OPEN_QUESTION = 'null';

    public function getEnumIsCorrectByTeacher(): string
    {
        return match ($this) {
            QuestionType::QCM => 'question choix multiple',
            QuestionType::UNIQUE_CHOICE => 'question choix unique',
            QuestionType::OPEN_QUESTION => 'question ouverte',
        };
    }
}