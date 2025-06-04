<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static INSPECTIONS()
 * @method static static DEFICIENCIES()
 * @method static static USERS()
 * @method static static INSPECTION_NOTE()
 */
final class ModuleEnum extends Enum
{
    public const INSPECTIONS = 'inspections';
    public const DEFICIENCIES = 'deficiencies';
    public const USERS = 'users';
    public const INSPECTION_NOTE = 'inspection-note';
}
