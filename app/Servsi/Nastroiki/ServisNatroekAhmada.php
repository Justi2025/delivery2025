<?php


namespace App\Servsi\Nastroiki;

use App\Khranilischa\Nastroiki\AdminSettingsRepository;
use JetBrains\PhpStorm\ArrayShape;

class ServisNatroekAhmada
{

    public function __construct(
        private readonly AdminSettingsRepository $adminSettingsRepository
    )
    {
    }

    public function getAllSettings()
    {
        return $this->adminSettingsRepository->getSettings();
    }

    public function setStandardBonus(int $amount): int
    {
        return $this->adminSettingsRepository->setStandardBonus($amount);
    }

    public function getStandardBonus()
    {
        return $this->adminSettingsRepository->getStandardBonus();
    }

    public function setVipBonus(int $amount)
    {
        return $this->adminSettingsRepository->setVipBonus($amount);
    }

    public function getVipBonus()
    {
        return $this->adminSettingsRepository->getVipBonus();
    }

    public function setReportVisibilityForManager(bool $visible)
    {
        return $this->adminSettingsRepository->setReportVisibilityForManager($visible);
    }

    public function isReportsVisibleForManager()
    {
        return $this->adminSettingsRepository->isReportsVisibleForManager();
    }

    public function setTelegramNotifications(bool $status)
    {
        return $this->adminSettingsRepository->setTelegramNotifications($status);
    }

    public function getTelegramNotificationsStatus()
    {
        return $this->adminSettingsRepository->getTelegramNotificationsStatus();
    }


    /*public function encodeTelegramConnectArgs(int $user_id, int $status)
    {
        // Todo: potentially user can steal!!!
        // User can pass random number
        return sprintf('%d;%d', $user_id << 32, $status);
    }


    #[ArrayShape(['user_id' => 'int', 'status' => 'boolean'])]
    public function decodeTelegramConnectArgs(string $args)
    {
        if(!str_contains($args, ';')) {
            return false;
        }

        $delimited = explode(';', $args);

        if(count($delimited) !== 2) {
            return false;
        }

        $user_id = $delimited[0];
        $status = $delimited[1];

        return ['user_id' => $user_id >> 32, 'status' => $status];
    }*/
}