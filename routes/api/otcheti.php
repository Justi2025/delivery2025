<?php


use App\Http\Controllers\Otcheti\GlavniPoOtchetam;

Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'reports'], function () {
    Route::get('payments-by-day-and-pickup-points', [GlavniPoOtchetam::class, 'poluchitPlatejiPoDniamIPvz'])->name('reports.payments-by-day-and-pickup-points');
    Route::get('payments-by-day', [GlavniPoOtchetam::class, 'poluchitPlatejiPoDniam'])->name('reports.payments-by-day');
    Route::get('debts-by-customers', [GlavniPoOtchetam::class, 'dolgiKlientov'])->name('reports.debts-by-customers');
});