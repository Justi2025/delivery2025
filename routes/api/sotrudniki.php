<?php


use App\Http\Controllers\Sotrudniki\GlavniPoSotrudnikam;

Route::group(['middleware' => ['api.auth', 'api.access'], 'prefix' => 'employees'], function () {
    Route::get('/', [GlavniPoSotrudnikam::class, 'poluchitVsekh'])->name('employees.get_all');
    Route::get('/couriers', [GlavniPoSotrudnikam::class, 'poluchitKurierov'])->name('employees.get_couriers');

    Route::get('{id}', [GlavniPoSotrudnikam::class, 'PolushitPoId'])->name('employee.get-by-id');
    Route::put('{id}', [GlavniPoSotrudnikam::class, 'obnovit'])->name('employee.update');
});