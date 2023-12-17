<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Authentication;
use App\Http\Controllers\Api\User\UserController;
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

Route::post('/Login', [Authentication::class,'login']);
Route::post('/Signup', [Authentication::class,'signup']);

Route::middleware('auth:sanctum')->group( function() {

    Route::delete('/Logout', [Authentication::class,'logout']);

    Route::group(['prefix' => 'Admin', 'as'=>'admin.', 'middleware' => ['admin']],function () {

        Route::controller(AdminController::class)->group(function () {
            Route::get('/Dashboard',      'Dashboard');
            Route::get('/Auth',           'userAuth');
            Route::get('/Users',          'Users'); //Users list
            Route::get('/Lawyers',        'Lawyers'); //Lawyers list
            Route::post('/Lawyer',        'lawyerCreate'); //create lawyer

            Route::get('/View/{id}',        'info'); //get specified userinfo
            Route::put('/Edit/{id}',      'update'); //update user info
            Route::delete('/Delete/{id}',    'delete'); //delete user

        });
    });

    Route::controller(UserController::class)->group(function () {
            Route::post('/Chat',                   'createChat'); //Users list
            Route::post('/LawyerChat',                   'LawyerChat'); //Users list
            Route::get('/Chats',                   'getChats'); //Users list
            Route::get('/Chat/{chatId}',           'getChatHistory'); //Users list
            Route::delete('/Chat/{id}',            'deleteChat'); //Users list
            Route::post('/User/Lawyers',                'searchLawyer'); //Users list
            Route::get('/User/Lawyers/{id}',               'lawyerInfo'); //Users list
    });
});

