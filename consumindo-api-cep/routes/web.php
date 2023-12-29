<?php

use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\CepController;
use App\Services\CepServices;
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

Route::get('/', function (CepServices $cep) {
    return view('welcome');
});
Route::get('/cep/{cep}', CepController::class);
Route::resource('admin/clients',ClientsController::class);