<?php


namespace App\Servsi\Nastroiki;

use App\Khranilischa\Nastroiki\NastroikiPolzovateliaRepozitorii;

class ServicNatroekPolzovatelia
{

    public function __construct(
        private readonly NastroikiPolzovateliaRepozitorii $nastroikiPolzovateliaRepozitorii
    )
    {
    }

    public function getAllSettings(int $user_id)
    {
        return $this->nastroikiPolzovateliaRepozitorii->poluchitNastroiki($user_id);
    }


    public function setTelegramNotifications(bool $status, int $user_id)
    {
        return $this->nastroikiPolzovateliaRepozitorii->setTelegramNotifications($status, $user_id);
    }

    public function getTelegramNotificationsStatus(int $user_id)
    {
        return $this->nastroikiPolzovateliaRepozitorii->getTelegramNotificationsStatus($user_id);
    }


    public function encodeTelegramConnectArgs(int $user_id, int $status)
    {
        return sprintf('%d;%d', $user_id << 32, $status);
    }


    public function decodeTelegramConnectArgs(string $args)
    {
        if (!str_contains($args, ';')) {
            return false;
        }

        $delimited = explode(';', $args);

        if (count($delimited) !== 2) {
            return false;
        }

        $user_id = $delimited[0];
        $status = $delimited[1];

        return ['user_id' => $user_id >> 32, 'status' => $status];
    }
}