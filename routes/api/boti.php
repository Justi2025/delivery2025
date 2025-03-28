<?php

use App\Http\Controllers\GlavniPoTelegramBotu;

Route::group(['prefix' => 'bot'], function () {

   Route::get('/set-webhook', [GlavniPoTelegramBotu::class, 'setWebhook'])->name('bot.set_webhook');

   Route::get('', [GlavniPoTelegramBotu::class, 'start'])->name('bot.start');
   Route::post('/get-message', [GlavniPoTelegramBotu::class, 'webhookHandler'])->name('bot.get_message');

});