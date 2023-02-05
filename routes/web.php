<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pagesController;
use App\Http\Controllers\formController;
use App\Http\Controllers\parserController;
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



Route::middleware(['auth'])->group(function () {

    Route::get('/', [pagesController::class, 'index']);
    Route::get('/players', [pagesController::class, 'players']);
    Route::post('/players', [pagesController::class, 'players']);
    Route::get('/player/{id}', [pagesController::class, 'playerOne']);
    Route::get('/tours', [pagesController::class, 'tours']);
    Route::post('/tours', [pagesController::class, 'tours']);
    Route::get('/tour/{id}', [pagesController::class, 'oneTour']);

    Route::get('/edit', [pagesController::class, 'editTours']);
    Route::post('/edit', [pagesController::class, 'editTours']);
    Route::get('/edit/games/{id}', [pagesController::class, 'editGamesTour']);
    Route::get('/insert/tour',  function () { return view('insertTour'); });

    Route::get('/insert/onetour',  function () { return view('insertNewTour'); });
    Route::get('/insert/select/tour', [pagesController::class, 'toursEditGames']);
    Route::post('/insert/select/tour', [pagesController::class, 'toursEditGames']);
    Route::get('/info', [pagesController::class, 'info']);
    Route::get('/insert/parser/po', [parserController::class, 'parserPo']);
    Route::post('/insert/parser/playoff', [formController::class, 'parserGamePlayoff']);
    Route::post('/edit/tour', [pagesController::class, 'editOneTour']);
    Route::post('/tour/update', [pagesController::class, 'updateTour']);
    Route::post('/update/onegame/{id}', [formController::class, 'updateOneGame']);
    Route::post('/delete/onegame/{id}', [formController::class, 'deleteOneGame']);
    Route::post('/delete/tour', [formController::class, 'deleteTour']);
    Route::post('/new_player', [formController::class, 'newPlayer']);
    Route::post('/update_player', [formController::class, 'updatePlayer']);
    Route::post('/delete_player', [formController::class, 'deletePlayer']);
    Route::post('/tour/new', [formController::class, 'newTour']);
    Route::post('/insert/games', [formController::class, 'insertGames']);
    Route::post('/insert/add/game', [formController::class, 'addGames']);
    Route::post('/update/user', [formController::class, 'updateUser']);
    Route::post('/parser', [parserController::class, 'parser']);
    Route::post('/insert/parser', [formController::class, 'parsGames']);
    Route::post('/update/password', [formController::class, 'updatePassword']);
    });
    

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


