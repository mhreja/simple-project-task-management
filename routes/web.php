<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FirstLoginController;


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
    return redirect()->route('dashboard');
});

Route::get('/first-login/change-password', [FirstLoginController::class, 'index'])->name('firstLogin.changePass');
Route::post('/first-login/change-password', [FirstLoginController::class, 'resetPassword'])->name('firstLogin.resetPassword');


Route::middleware(['auth:sanctum', 'verified'])->group(function(){
    
    /* Routes for all logged in & verified & first_login_done users */
    Route::middleware(['firstlogin'])->group(function(){
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });


    /* Routes for all admins only */
    Route::middleware(['firstlogin', 'admin'])->group(function(){    
        Route::view('/users', 'admin.users')->name('users');
        Route::view('/projects', 'admin.projects')->name('projects');
        Route::view('/projects/assignings', 'admin.projects-assignings')->name('projects.assignings');
        Route::view('task/management', 'admin.task-management')->name('task.management');
    });      
});



