<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 🚀 Override Tyro's default home route to display our custom POS analytics
Route::middleware(['web', 'auth'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('tyro-dashboard.index'); // 🚀 This name must match Tyro's configuration!