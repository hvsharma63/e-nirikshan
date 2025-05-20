<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionEnum extends Enum
{
    // CORE ACTIONS - own vs. all
    public const LIST_OWN    = 'list-own';
    public const VIEW_OWN    = 'view-own';
    public const DOWNLOAD_OWN    = 'download-own';
    public const CREATE_OWN  = 'create-own';
    public const EDIT_OWN    = 'edit-own';

    public const LIST_ALL    = 'list-all';
    public const VIEW_ALL    = 'view-all';
    public const DOWNLOAD_ALL    = 'download-all';
    public const CREATE_ALL  = 'create-all';
    public const EDIT_ALL    = 'edit-all';

    // MISCELLANEOUS GLOBAL PERMISSIONS
    public const VIEW_DASHBOARD        = 'view-dashboard';
    public const VIEW_DASHBOARD_STATS  = 'view-dashboard-stats';
    public const LIST_USERS            = 'list-users';

    public const REMIND    = 'send-reminder';
    public const ATTEND    = 'attend';
    public const DOWNLOAD  = 'download';

    //
    // ——— Helpers for module-scoped permissions ———
    //

    // Officer / own-data
    public static function listOwn(ModuleEnum $m): string
    {
        return self::LIST_OWN   . '-' . $m->value;
    }
    public static function viewOwn(ModuleEnum $m): string
    {
        return self::VIEW_OWN   . '-' . $m->value;
    }
    public static function downloadOwn(ModuleEnum $m): string
    {
        return self::DOWNLOAD_OWN   . '-' . $m->value;
    }
    public static function createOwn(ModuleEnum $m): string
    {
        return self::CREATE_OWN . '-' . $m->value;
    }
    public static function editOwn(ModuleEnum $m): string
    {
        return self::EDIT_OWN   . '-' . $m->value;
    }

    // Admin / all-data
    public static function listAll(ModuleEnum $m): string
    {
        return self::LIST_ALL   . '-' . $m->value;
    }
    public static function viewAll(ModuleEnum $m): string
    {
        return self::VIEW_ALL   . '-' . $m->value;
    }
    public static function downloadAll(ModuleEnum $m): string
    {
        return self::DOWNLOAD_ALL   . '-' . $m->value;
    }
    public static function createAll(ModuleEnum $m): string
    {
        return self::CREATE_ALL . '-' . $m->value;
    }
    public static function editAll(ModuleEnum $m): string
    {
        return self::EDIT_ALL   . '-' . $m->value;
    }

    // Miscellaneous (module-scoped)
    public static function remind(ModuleEnum $m): string
    {
        return self::REMIND   . '-' . $m->value;
    }
    public static function attend(ModuleEnum $m): string
    {
        return self::ATTEND   . '-' . $m->value;
    }
    public static function download(ModuleEnum $m): string
    {
        return self::DOWNLOAD . '-' . $m->value;
    }

    //
    // ——— Global (no module) getters ———
    //

    public static function viewDashboard(): string
    {
        return self::VIEW_DASHBOARD;
    }
    public static function viewDashboardStats(): string
    {
        return self::VIEW_DASHBOARD_STATS;
    }
    public static function listUsers(): string
    {
        return self::LIST_USERS;
    }
}
