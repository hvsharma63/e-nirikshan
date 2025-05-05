<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ModuleEnum extends Enum
{
    const INSPECTIONS = 'inspections';
    const DEFICIENCIES = 'deficiencies';
    const USERS = 'users';
    const INSPECTION_NOTE = 'inspection-note';
}
