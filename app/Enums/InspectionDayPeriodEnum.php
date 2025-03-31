<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Carbon\Carbon;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class InspectionDayPeriodEnum extends Enum
{
    const MORNING = 0;
    const AFTERNOON = 1;
    const EVENING = 2;
    const NIGHT = 3;

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
