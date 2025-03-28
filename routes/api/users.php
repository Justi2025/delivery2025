<?php


use App\Http\Controllers\GlavniPoPolzovateliam;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;

Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'users'], function () {
    Route::get('/', [GlavniPoPolzovateliam::class, 'index'])->name('users.index');
    Route::post('update-passport', [GlavniPoPolzovateliam::class, 'updatePassport'])->name('users.update-passport');
    Route::put('{user}', [GlavniPoPolzovateliam::class, 'update'])->name('users.update');
});
