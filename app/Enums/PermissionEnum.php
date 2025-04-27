<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionEnum extends Enum
{
    const VIEW_INDEX = 'view-index';
    const VIEW = 'view';
    const CREATE = 'create';
    const REMIND = 'send-reminder';
    const attend = 'attend';
    const download = 'download';

    const VIEW_ALL_INDEX = 'view-all-index';

    public static function viewIndexPermissionFor(ModuleEnum $module): string
    {
        return self::VIEW_INDEX .'-'. $module->value;
    }

    public static function viewAllIndexPermissionFor(ModuleEnum $module): string
    {
        return self::VIEW_ALL_INDEX .'-'. $module->value;
    }

    public static function viewPermissionFor(ModuleEnum $module): string
    {
        return self::VIEW .'-'. $module->value;
    }

    public static function createPermissionFor(ModuleEnum $module): string
    {
        return self::CREATE .'-'. $module->value;
    }

    public static function remindPermissionFor(ModuleEnum $module): string
    {
        return self::REMIND .'-'. $module->value;
    }

    public static function attendPermissionFor(ModuleEnum $module): string
    {
        return self::attend .'-'. $module->value;
    }

    public static function downloadPermissionFor(ModuleEnum $module): string
    {
        return self::download .'-'. $module->value;
    }
}
