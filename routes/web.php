<?php

use App\Events\SendMessageEvent;
use App\Livewire\ChatAppComponent;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('chats', 'chats')
    ->middleware(['auth', 'verified'])
    ->name('chats');

Route::view('users', 'users')
    ->middleware(['auth', 'verified'])
    ->name('users');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::get('/test', function () {

    broadcast(new SendMessageEvent());
});
require __DIR__ . '/auth.php';
