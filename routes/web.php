<?php

use App\Http\Controllers\UserController;
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

Route::get('/',[UserController::class,'index']);
Route::post('/save',[UserController::class,'save'])->name('save.data');
Route::get('/edit/{id}',[UserController::class,'edit'])->name('user.edit');
Route::get('/reload',[UserController::class,'reloadData'])->name('user.reload');
Route::delete('/delete/{id}',[UserController::class,'delete'])->name('user.delete');
Route::post('/update_user/{id}',[UserController::class,'update'])->name('update.data');
Route::post('/bulk_delete',[UserController::class,'bulk_delete'])->name('user.bulkdelete');

