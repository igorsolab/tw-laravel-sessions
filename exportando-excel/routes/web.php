<?php

use App\Http\Controllers\Admin\ClientsController;
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
Route::resource('admin/clients',ClientsController::class);
Route::get('admin/clients/all/excel',[ClientsController::class,'allClientsExcel']);
Route::get('admin/clients/search/excel',[ClientsController::class,'searchClientsRequest']);
Route::post('admin/import/clients/excel',[ClientsController::class,'importClientsRequest']);