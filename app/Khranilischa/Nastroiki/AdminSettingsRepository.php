<?php
 

namespace App\Khranilischa\Nastroiki;

use App\Models\Settings\AdminSettings;
use Illuminate\Support\Facades\DB;

class AdminSettingsRepository
{
    private const StandardBonusKey = 'standard_bonus';
    private const VipBonusKey = 'vip_bonus';
    private const ReportVisibility = 'reports_visibility';
    private const TgNotifications = 'telegram_notifications';


    public function getSettings()
    {
        $collection = AdminSettings::get(['setting_id', 'value']);
        return $this->transformSettings($collection->toArray());
    }


    public function setStandardBonus(int $amount): int
    {
        return AdminSettings::upsert(
            ['setting_id' => self::StandardBonusKey, 'value' => $amount],
            ['setting_id'],
            ['value']
        );
    }

    public function getStandardBonus(): int
    {
        $sql = "select value from admin_settings where setting_id = :setting_id";
        return DB::selectOne($sql, ['setting_id' => self::StandardBonusKey])->value;
    }

    public function setVipBonus(int $amount): int
    {
        return AdminSettings::upsert(
            ['setting_id' => self::VipBonusKey, 'value' => $amount],
            ['setting_id'],
            ['value']
        );
    }

    public function getVipBonus(): int
    {
        $sql = "select value from admin_settings where setting_id = :setting_id";
        return DB::selectOne($sql, ['setting_id' => self::VipBonusKey])?->value;
    }

    public function setReportVisibilityForManager(bool $visible): int
    {
        return AdminSettings::upsert(
            ['setting_id' => self::ReportVisibility, 'value' => $visible],
            ['setting_id'],
            ['value']
        );
    }

    public function isReportsVisibleForManager(): bool
    {
        $sql = 'select value from admin_settings where setting_id = :setting_id';
        return DB::selectOne($sql, ['setting_id' => self::ReportVisibility])?->value ?? false;
    }


    public function setTelegramNotifications(bool $enabled): int
    {
        return AdminSettings::upsert(
            ['setting_id' => self::TgNotifications, 'value' => $enabled],
            ['setting_id'],
            ['value']
        );
    }

    public function getTelegramNotificationsStatus(): bool
    {
        $sql = 'select value from admin_settings where setting_id = :setting_id';
        return DB::selectOne($sql, ['setting_id' => self::TgNotifications])?->value ?? false;
    }


    private function transformSettings(array $initial): array
    {
        $transformedArray = [];

        foreach ($initial as $item) {
            $transformedArray[$item['setting_id']] = $item['value'];
        }

        return $transformedArray;
    }
}