<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Carbon\Carbon;

/**
 * @method static static MORNING()
 * @method static static AFTERNOON()
 * @method static static EVENING()
 * @method static static NIGHT()
 */
final class InspectionDayPeriodEnum extends Enum
{
    public const MORNING = 0;
    public const AFTERNOON = 1;
    public const EVENING = 2;
    public const NIGHT = 3;

    public static function getPeriodFromDateTime(Carbon $dateTime): int
    {
        $hour = (int) $dateTime->format('H');

        if ($hour >= 5 && $hour < 12) {
            return self::MORNING;
        }

        if ($hour >= 12 && $hour < 17) {
            return self::AFTERNOON;
        }

        if ($hour >= 17 && $hour < 20) {
            return self::EVENING;
        }

        return self::NIGHT;
    }
}
