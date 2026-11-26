<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ArrangementController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Middleware\EnsurePlayerSession;
use App\Http\Middleware\RequireExportPassword;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'welcome'])->name('game.welcome');
Route::post('/players', [PlayerController::class, 'store'])->name('players.store');

Route::middleware(EnsurePlayerSession::class)->group(function (): void {
    $groups = array_map(strval(...), array_keys(config('symphony.groups')));

    Route::get('/groups', [GameController::class, 'groups'])->name('game.groups');
    Route::get('/groups/{group}', [AnswerController::class, 'create'])->whereIn('group', $groups)->name('answers.create');
    Route::post('/groups/{group}/answers', [AnswerController::class, 'store'])->whereIn('group', $groups)->name('answers.store');

    Route::get('/answers/{answer}/board', [BoardController::class, 'show'])->name('board.show');
    Route::post('/answers/{answer}/arrangements', [ArrangementController::class, 'store'])->name('arrangements.store');
    Route::get('/arrangements/{arrangement}', [ArrangementController::class, 'show'])->name('arrangements.show');
});

// Every player with their answers, as a page and as PDF / Excel downloads. Needs EXPORT_PASSWORD in .env.
Route::middleware(['throttle:30,1', RequireExportPassword::class])->prefix('export')->name('export.')->group(function (): void {
    Route::get('/', [ExportController::class, 'index'])->name('index');
    Route::get('/pdf', [ExportController::class, 'pdf'])->name('pdf');
    Route::get('/excel', [ExportController::class, 'excel'])->name('excel');
});
