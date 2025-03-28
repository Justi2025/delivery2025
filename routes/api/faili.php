<?php

use App\Http\Controllers\GlavniPoFailam;


Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'files'], function () {
    Route::get('/', [GlavniPoFailam::class, 'index'])->name('files.index');
    Route::post('upload', [GlavniPoFailam::class, 'store'])->name('files.store');
    Route::delete('{file}', [GlavniPoFailam::class, 'udalitFail'])->name('files.destroy');
    Route::post('/search', [GlavniPoFailam::class, 'poiskFailov'])->name('files.search');
});

