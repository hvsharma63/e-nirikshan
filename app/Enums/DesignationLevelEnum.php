<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DesignationLevelEnum extends Enum
{
    public const ADMINISTRATIVE_OFFICER = 0;
    public const BRANCH_OFFICER = 1;
    public const ASSISTANT_BRANCH_OFFICER = 2;
}
