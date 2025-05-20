<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InspectionStatusEnum extends Enum
{
    public const DRAFT = 0;
    public const PROGRESS = 1;
    public const COMPLETED = 2;
}
