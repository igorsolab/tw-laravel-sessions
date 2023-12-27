<?php

use App\Http\Controllers\AlunoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::get('/alunos',[AlunoController::class, 'index'])->name('alunos.index');
// Route::get('/alunos/{aluno}',[AlunoController::class, 'show'])->name('alunos.show');
// Route::post('/alunos',[AlunoController::class,'store'])->name('alunos.store');
// Route::put('/alunos/{aluno}',[AlunoController::class, 'update'])->name('alunos.update');
// Route::delete('/alunos/{aluno}',[AlunoController::class, 'destroy'])->name('alunos.destroy');

/**
 * 
 * Substituição da rota dos alunos
 * Cuidado ao usar os metodos, pois ele pega o padrão [index,store,show,update,destroy]
 */
Route::apiResource('alunos',AlunoController::class);