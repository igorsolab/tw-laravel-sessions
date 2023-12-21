<?php

use App\Http\Controllers\CarrinhoController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/carrinho/listar', [CarrinhoController::class, 'listar'])->name('carrinho.listar');
Route::get('/carrinho/adicionar',[CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::get('/carrinho/remover',[CarrinhoController::class, 'remover'])->name('carrinho.remover');