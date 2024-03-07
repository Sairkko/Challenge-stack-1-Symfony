<?php

namespace App\Enum;

enum QuizzType: string
{
    case QCMN = 'qcmn';
    case NORMAL = 'normal';

    public function getEnumQuizzType(): string
    {
        return match ($this) {
            QuizzType::QCMN => 'qcm a point négatif',
            QuizzType::NORMAL => 'qcm normal',
        };
    }
}
