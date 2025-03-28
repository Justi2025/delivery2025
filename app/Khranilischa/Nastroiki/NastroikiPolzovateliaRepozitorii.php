<?php


namespace App\Khranilischa\Nastroiki;

use App\Models\Settings\UserSettings;
use Illuminate\Support\Facades\DB;

class NastroikiPolzovateliaRepozitorii extends BaseSettings
{
    private const TgNotifications = 'telegram_notifications';


    public function poluchitNastroiki(int $user_id)
    {
        $collection = UserSettings::where(['user_id' => $user_id])->get(['setting_id', 'value']);
        return $this->transformSettings($collection->toArray());
    }

    public function setTelegramNotifications(bool $enabled, int $user_id): int
    {
        return UserSettings::upsert(
            ['user_id' => $user_id, 'setting_id' => self::TgNotifications, 'value' => $enabled],
            ['user_id', 'setting_id'],
            ['value']
        );
    }

    public function getTelegramNotificationsStatus(int $user_id): bool
    {
        $sql = 'select value from users_settings where user_id = :user_id and setting_id = :setting_id';
        return DB::selectOne($sql, ['user_id' => $user_id, 'setting_id' => self::TgNotifications])->value;
    }

}