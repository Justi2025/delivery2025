<?php


use App\Http\Controllers\GlavniPoKompaniam;

Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'companies'], function () {
    Route::post('', [GlavniPoKompaniam::class, 'create'])->name('companies.create');
    Route::get('', [GlavniPoKompaniam::class, 'index'])->name('companies.index');
    Route::get('{model}', [GlavniPoKompaniam::class, 'get'])->name('companies.get');
    Route::put('{model}', [GlavniPoKompaniam::class, 'update'])->name('companies.update');
});