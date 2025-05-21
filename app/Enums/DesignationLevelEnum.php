<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ADMINISTRATIVE_OFFICER()
 * @method static static BRANCH_OFFICER()
 * @method static static ASSISTANT_BRANCH_OFFICER()
 */
final class DesignationLevelEnum extends Enum
{
    public const ADMINISTRATIVE_OFFICER = 0;
    public const BRANCH_OFFICER = 1;
    public const ASSISTANT_BRANCH_OFFICER = 2;
}
