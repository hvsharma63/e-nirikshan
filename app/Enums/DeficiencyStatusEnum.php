<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DeficiencyStatusEnum extends Enum
{
    const PENDING = 0;
    const SEEN = 1;
    const ATTENEDED = 2;
}
