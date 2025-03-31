<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InspectionStatusEnum extends Enum
{
    const DRAFT = 0;
    const PROGRESS = 1;
    const COMPLETED = 2;
}
