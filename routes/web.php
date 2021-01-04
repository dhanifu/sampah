<?php

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

Auth::routes();

Route::get('/', function(){return redirect()->route('dashboard');});

Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

Route::name('admin.')->middleware('role:admin')->group(function(){
    Route::prefix('type')->name('type.')->group(function(){
        Route::get('/', 'Admin\TypeController@index')->name('index');
        Route::post('/', 'Admin\TypeController@store')->name('store');
        Route::put('/update/{type}', 'Admin\TypeController@update')->name('update');
        Route::delete('/delete/{type}', 'Admin\TypeController@destroy')->name('destroy');

        Route::prefix('trash')->name('trash.')->group(function(){
            Route::get('/', 'Admin\TypeController@trash')->name('index');
            Route::get('/restore/{id}', 'Admin\TypeController@restoreData')->name('restore');
            Route::get('/permanent-delete/{id}', 'Admin\TypeController@deleteData')->name('delete');
            Route::get('/restore/all/by/{uuid}', 'Admin\TypeController@restoreAllData')->name('restoreall');
            Route::get('/permanent-delete/all/by/{uuid}', 'Admin\TypeController@deleteAllData')->name('deleteall');
        });
    });

    Route::prefix('village')->name('village.')->group(function(){
        Route::get('/', 'Admin\VillageController@index')->name('index');
        Route::post('/', 'Admin\VillageController@store')->name('store');
        Route::put('/update/{village}', 'Admin\VillageController@update')->name('update');
        Route::delete('/delete/{village}', 'Admin\VillageController@destroy')->name('destroy');

        Route::prefix('trash')->name('trash.')->group(function(){
            Route::get('/', 'Admin\VillageController@trash')->name('index');
            Route::get('/restore/{id}', 'Admin\VillageController@restoreData')->name('restore');
            Route::get('/permanent-delete/{id}', 'Admin\VillageController@deleteData')->name('delete');
            Route::get('/restore/all/by/{uuid}', 'Admin\VillageController@restoreAllData')->name('restoreall');
            Route::get('/permanent-delete/all/by/{uuid}', 'Admin\VillageController@deleteAllData')->name('deleteall');
        });
    });
});


Route::name('operator.')->middleware('role:operator')->group(function(){
    Route::prefix('member')->name('member.')->group(function(){
        Route::name('data.')->group(function(){
            Route::get('/', 'Operator\MemberController@index')->name('index');
            Route::get('/add', 'Operator\MemberController@create')->name('create');
            Route::post('/add', 'Operator\MemberController@store')->name('store');
            Route::get('/{member}/edit', 'Operator\MemberController@edit')->name('edit');
            Route::put('/{member}/edit', 'Operator\MemberController@update')->name('update');
            Route::delete('/{member}/movetotrash', 'Operator\MemberController@destroy')->name('destroy');
        });
        Route::prefix('trash')->name('trash.')->group(function(){
            Route::get('/', 'Operator\MemberController@trash')->name('index');
            Route::get('/restore/{id}', 'Operator\MemberController@restoreData')->name('restore');
            Route::get('/permanent-delete/{id}', 'Operator\MemberController@deleteData')->name('delete');
            Route::get('/restore/all/by/{uuid}', 'Operator\MemberController@restoreAllData')->name('restoreall');
            Route::get('/permanent-delete/all/by/{uuid}', 'Operator\MemberController@deleteAllData')->name('deleteall');
        });
    });
});