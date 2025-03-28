<?php

use App\Http\Controllers\GlavniPoVkhoduNaSait;


Route::group(['middleware' => ['api.auth'], 'prefix' => 'auth'], function () {
    Route::post('register', [GlavniPoVkhoduNaSait::class, 'register'])->name('auth.register')->withoutMiddleware('api.auth');
    Route::post('login', [GlavniPoVkhoduNaSait::class, 'login'])->name('auth.login')->withoutMiddleware('api.auth');
    Route::post('send-code', [GlavniPoVkhoduNaSait::class, 'sendCode'])->name('auth.sendCode')->withoutMiddleware('api.auth');
    Route::post('check-code', [GlavniPoVkhoduNaSait::class, 'checkCode'])->name('auth.checkCode')->withoutMiddleware('api.auth');

    Route::post('send-recovery-code', [GlavniPoVkhoduNaSait::class, 'sendRecoveryCode'])->name('auth.send_recovery_code')->withoutMiddleware('api.auth');
    Route::post('change-password', [GlavniPoVkhoduNaSait::class, 'changePassword'])->name('auth.change_password')->withoutMiddleware('api.auth');

    Route::get('refresh', [GlavniPoVkhoduNaSait::class, 'refresh'])->name('auth.refresh')->withoutMiddleware('api.auth');
    Route::get('logout', [GlavniPoVkhoduNaSait::class, 'logout'])->name('auth.logout');

    Route::get('get-authenticated', [GlavniPoVkhoduNaSait::class, 'getAuthenticated'])->name('customers.get-authenticated');
});