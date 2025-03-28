<?php


use App\Http\Controllers\GlavniiPoPvz;

Route::group(['middleware' => ['api.auth', 'api.log', 'api.access'], 'prefix' => 'delivery-points'], function () {
    Route::post('', [GlavniiPoPvz::class, 'sozdat'])->name('delivery_points.create');
    Route::get('', [GlavniiPoPvz::class, 'index'])->name('delivery_points.index');
    Route::get('{deliveryPoint}', [GlavniiPoPvz::class, 'poluchit'])->name('delivery_points.get');
    Route::put('{deliveryPoint}', [GlavniiPoPvz::class, 'update'])->name('delivery_points.update');
    Route::put('{deliveryPoint}/usage-frequency', [GlavniiPoPvz::class, 'updateUsageFrequency'])->name('delivery_points.update-usage-frequency');
});