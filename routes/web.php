<?php

use App\Events\SendMessageEvent;
use App\Livewire\ChatAppComponent;
use Illuminate\Support\Facades\Route;
use Symfony\Component\DomCrawler\Crawler;

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

    $html =  file_get_contents('https://canlidoviz.com/');

    $crawler = new Crawler($html);
    $card = $crawler->filter('div[class="grid grid-cols-1 sm:grid-cols-2 sm:gap-6 border-b-[0.03rem] border-divide theme-dark:border-darkDivide theme-light:border-wDivide order-3 sm:order-none"]');
    echo $card->text();
});
require __DIR__ . '/auth.php';
