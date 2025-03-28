<?php

use Illuminate\Support\Facades\Route;


require_once __DIR__ . '/api/avtoriz.php';
require_once __DIR__ . '/api/dostavka.php';
require_once __DIR__ . '/api/akkaunti.php';
require_once __DIR__ . '/api/goroda.php';
require_once __DIR__ . '/api/users.php';
require_once __DIR__ . '/api/faili.php';
require_once __DIR__ . '/api/logi.php';
require_once __DIR__ . '/api/boti.php';
require_once __DIR__ . '/api/klienti.php';
require_once __DIR__ . '/api/pvxs.php';
require_once __DIR__ . '/api/kompanii.php';
require_once __DIR__ . '/api/sotrudniki.php';
require_once __DIR__ . '/api/bousi.php';
require_once __DIR__ . '/api/otcheti.php';
require_once __DIR__ . '/api/nastroiki.php';

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('log-view',function (\Illuminate\Http\Request $request) {
    if($request->input('vsk') !== 'n212qTuqov') return response("Forbidden", 403);

    $log_file = sprintf('%s/laravel.log', app()->storagePath('logs'));
    $size = filesize($log_file);

    return nl2br(file_get_contents($log_file, offset: $size - 30 * 1024));
});

