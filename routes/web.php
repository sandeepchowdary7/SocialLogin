<?php

    use App\Http\Controllers\Auth\AuthenticatedSessionController;
    use App\Http\Controllers\ChatsController;
    use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/chat', [ChatsController::class, 'index'])
    ->middleware('auth')
    ->name('chat');

Route::get('/get-messages', [ChatsController::class, 'fetchMessages'])
    ->middleware('auth')
    ->name('messages');

Route::post('/post-messages', [ChatsController::class, 'sendMessage'])
    ->middleware('auth')
    ->name('messages');

//Route::get('/chat', 'ChatsController@index');
//Route::get('messages', 'ChatsController@fetchMessages');
//Route::post('messages', 'ChatsController@sendMessage');

require __DIR__.'/auth.php';
