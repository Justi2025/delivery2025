<?php


use App\Http\Controllers\NastroikiSaita\GlavniPoNastroikamAhmada;
use App\Http\Controllers\NastroikiSaita\GlavniPoNastroikamPolzovatelia;

Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'settings'], function () {
    Route::get('all', [GlavniPoNastroikamAhmada::class, 'poluchitVse'])->name('settings.all-settings');
    Route::post('set-standard-bonus', [GlavniPoNastroikamAhmada::class, 'ustanovitStandartniBonus'])->name('settings.set-standard-bonus');
    Route::post('set-vip-bonus', [GlavniPoNastroikamAhmada::class, 'ustanovitVipBonus'])->name('settings.set-vip-bonus');
    Route::post('set-reports-visibility', [GlavniPoNastroikamAhmada::class, 'ustanovitVidimostOtchetov'])->name('settings.reports-visibility');
});


Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'user-settings'], function () {
    Route::get('all', [GlavniPoNastroikamPolzovatelia::class, 'getAll'])->name('user-settings.all-settings');
    Route::post('set-telegram-notifications', [GlavniPoNastroikamPolzovatelia::class, 'setTelegramNotifications'])->name('user-settings.set-tg-notifications');
});