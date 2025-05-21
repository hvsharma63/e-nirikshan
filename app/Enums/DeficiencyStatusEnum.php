<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static SEEN()
 * @method static static ATTENDED()
 */
final class DeficiencyStatusEnum extends Enum
{
    public const PENDING = 0;
    public const SEEN = 1;
    public const ATTENDED = 2;
}
