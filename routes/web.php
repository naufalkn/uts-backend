<?php

use App\Http\Controllers\Admin72Controller;
use App\Http\Controllers\Login72Controller;
use App\Http\Controllers\Users72Controller;
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
    return redirect('/register72');
});

Route::group(['middleware' => ['isNotLogged']], function () {
    // Login & Register
    Route::view('/register72', 'register');
    Route::view('/login72', 'login');
    Route::post('/register72', [Login72Controller::class, 'registerHandler72']);
    Route::post('/login72', [Login72Controller::class, 'loginHandler72']);
});

Route::group(['middleware' => ['isUser']], function () {
    // dashboard user
    Route::get('/profile72', [Users72Controller::class, 'profilePage72']);

    //change Password
    Route::get('/changePassword72', [Users72Controller::class, 'editPasswordPage72']);
    Route::post('/updatePassword72', [Users72Controller::class, 'updatePassword72']);

    // edit profile user
    Route::post('/updateProfil72', [Users72Controller::class, 'updateProfil72']);
    Route::post('/uploadPhotoProfil72', [Users72Controller::class, 'uploadPhotoProfil72']);
    Route::post('/uploadPhotoKTP72', [Users72Controller::class, 'uploadPhotoKTP72']);
});

Route::group(['middleware' => ['isAdmin']], function () {
    //dashboard && detail user
    Route::get('/dashboard72', [Admin72Controller::class, 'dashboardPage72']);
    Route::get('/detail72/{id}', [Admin72Controller::class, 'detailPage72']);

    // update user
    Route::get('/update72/user/{id}/status', [Admin72Controller::class, 'updateUserStatus72']);
    Route::post('/update72/user/{id}/agama', [Admin72Controller::class, 'updateUserAgama72']);

    // CRUD AGAMA
    // Show all agama
    Route::get("/agama72", [Admin72Controller::class, "agamaPage72"]);
    // add agama
    Route::post("/agama72", [Admin72Controller::class, "createAgama72"]);
    // show edit agama & update agama
    Route::get("/agama72/{id}/edit", [Admin72Controller::class, 'editAgamaPage72']);
    Route::post("/agama72/{id}/update", [Admin72Controller::class, 'updateAgama72']);
    // delete agama
    Route::get("/agama72/{id}/delete", [Admin72Controller::class, 'deleteAgama72']);
});

Route::get('/logout72', [Login72Controller::class, 'logoutHandler72'])->middleware('isLogged');
