<?php

use App\Http\Controllers\TransactionController;
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

Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
Route::get('/transaction/create', [TransactionController::class, 'create'])->name('transaction.create');
Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');
Route::get('/transaction/edit/{id}', [TransactionController::class, 'edit'])->name('transaction.edit');
Route::post('/transaction/update/{id}', [TransactionController::class, 'update'])->name('transaction.update');
Route::get('/transaction/show/{id}', [TransactionController::class, 'show'])->name('transaction.show');
Route::delete('/transaction/delete/{id}', [TransactionController::class, 'delete'])->name('transaction.delete');


