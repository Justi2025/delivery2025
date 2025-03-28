<?php


use App\Http\Controllers\AkkauntiController;

Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'account'], function () {
    Route::post('/create-by-employee', [AkkauntiController::class, 'sozdatAkkauntSotrudnikom'])->name('account.create_by_employee');
    Route::post('/avatar', [AkkauntiController::class, 'obnovitAvatar'])->name('account.avatar-update');
    Route::post('/address', [AkkauntiController::class, 'obnovitAdres'])->name('account.address-update');
    Route::post('/change-password', [AkkauntiController::class, 'izmenitParol'])->name('account.change-password');
});