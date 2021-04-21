<?php

use App\Http\Controllers\EmployeeController;
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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employee.index');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employee.store');
    Route::patch('employees/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
});
