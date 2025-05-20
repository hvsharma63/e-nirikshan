<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ModuleEnum extends Enum
{
    public const INSPECTIONS = 'inspections';
    public const DEFICIENCIES = 'deficiencies';
    public const USERS = 'users';
    public const INSPECTION_NOTE = 'inspection-note';
}
