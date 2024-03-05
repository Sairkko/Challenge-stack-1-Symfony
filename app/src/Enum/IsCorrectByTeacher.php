<?php

namespace App\Enum;

enum IsCorrectByTeacher: string
{
    case TRUE = 'true';
    case FALSE = 'false';
    case NULL = 'null';

    public function getEnumIsCorrectByTeacher(): string
    {
        return match ($this) {
            IsCorrectByTeacher::TRUE => 'question juste',
            IsCorrectByTeacher::FALSE => 'question fausse',
            IsCorrectByTeacher::NULL => 'question non corrigée',
        };
    }
}