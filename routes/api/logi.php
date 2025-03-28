<?php

use App\Http\Controllers\GlavniPoLogamPolzovatelei;
use Illuminate\Routing\Router;

Route::group(['middleware' => ['api.auth', 'api.access'], 'prefix' => 'logs'], function (Router $router) {
    $router->get('/', [GlavniPoLogamPolzovatelei::class, 'index'])->name('logs.list');
    $router->post('/', [GlavniPoLogamPolzovatelei::class, 'index'])->name('logs.filter');
    $router->get('/ipsinfo', [GlavniPoLogamPolzovatelei::class, 'getUsersIpInfo'])->name('logs.ipsinfo'); // Todo: routes with exact names should be first of parametrized routes
    $router->get('/most-active-users', [GlavniPoLogamPolzovatelei::class, 'getMostActiveUsers'])->name('logs.most-active');
    $router->get('/{user_id}', [GlavniPoLogamPolzovatelei::class, 'show'])->name('logs.for-user');
});

