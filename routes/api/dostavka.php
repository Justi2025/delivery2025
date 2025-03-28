<?php


use App\Http\Controllers\Sdelki\GlavniPoSdelkam;

Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'dostavka'], function () {

    Route::get('/', [GlavniPoSdelkam::class, 'poluchitVseZakazi'])->name('dostavka.index');
    Route::post('status', [GlavniPoSdelkam::class, 'izmeniStatusSdelki'])->name('dostavka.change-status');
    Route::post('incoming-delivery', [GlavniPoSdelkam::class, 'sozdatSdelku'])->name('dostavka.incoming-delivery');
    Route::put('assign-to-courier', [GlavniPoSdelkam::class, 'naznachitKurieru'])->name('dostavka.assign-to-courier');

    Route::get('waiting-for-customers', [GlavniPoSdelkam::class, 'gotoviKVidache'])->name('dostavka.waiting-for-customers');
    Route::get('issued', [GlavniPoSdelkam::class, 'vidanniePokupateliu'])->name('dostavka.issued');
    Route::get('cancelled', [GlavniPoSdelkam::class, 'poluchitOtmenennieZakazi'])->name('dostavka.cancelled');

    Route::put('update-incoming-order', [GlavniPoSdelkam::class, 'obnovitSdelku'])->name('dostavka.update-incoming');
    Route::post('cancel', [GlavniPoSdelkam::class, 'otmeniSdelku'])->name('dostavka.cancel');
    Route::post('change-status-to', [GlavniPoSdelkam::class, 'izmenitStatusNa'])->name('dostavka.change-status-to');
    Route::post('issue', [GlavniPoSdelkam::class, 'vidatZakaz'])->name('dostavka.issue');

    Route::get('/{id}', [GlavniPoSdelkam::class, 'poluchitSdelkuPoId'])->name('dostavka.get-by-id');
    Route::get('/{id}/get-contact', [GlavniPoSdelkam::class, 'poluchitNomertelephonaVladelcaZakaza'])->name('dostavka.get-customer-contact-by-order-id');
});