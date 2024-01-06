<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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


Route::group(['middleware'=> 'guest'],function(){
    Route::view('/', 'login')->name('login_view');
    Route::post('login',[UserController::class,'login'])->name('login');
});


Route::group(['prefix'=>'user','middleware'=> 'auth'],function(){
    Route::get('details',[UserController::class,'index'])->name('user_details');
    Route::get('manage/{id?}/{admin_id?}',[UserController::class,'user_view'])->name('user_manage');
    Route::get('delete/{id?}',[UserController::class,'user_delete'])->name('user_delete');
    Route::get('sub_user/details/{admin_id}',[UserController::class,'sub_users'])->name('sub_users');
    Route::get('addressess/{id?}',[UserController::class,'view_address'])->name('addressess');
    Route::post('manage',[UserController::class,'manage_user'])->name('user_manage');
    Route::get('logout',[UserController::class,'logout'])->name('user_logout');
});


