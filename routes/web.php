<?php

use App\Http\Controllers\ChirpController;
use Illuminate\Support\Facades\Route;

/*
auth middleware: only authed(logged-in) users can access the route
verified middleware: only email-verified(if enabled) users can access the route
*/
Route::view('/', 'welcome');

//CHIRPS ROUTE
Route::get('chirps', [ChirpController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('chirps');
//END CHIRPS ROUTE

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

/*
require __DIR__.'/auth.php' makes sure that the auth logic or config defined in auth.php is availabel in web.php (this file)

The purpose of this is to centralized the routes for auth only
Yes, exactly. You can define authentication-related routes directly in web.php, but for better organization and maintainability, it is common practice to separate them into a dedicated file like auth.php. This way, you can keep your route definitions modular and easier to manage.
*/
require __DIR__.'/auth.php'; #include authentication-related routes
