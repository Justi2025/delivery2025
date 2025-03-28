<?php

use App\Models\City;

Route::get('cities', fn() => City::all(['id', 'name']))->name('cities.list');
