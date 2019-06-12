<?php

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

setlocale(LC_TIME, 'ru_RU.UTF-8');

/**
 * Главная страница
 */
Route::get('/', ['as' => 'crm', 'uses' => 'CrmController@index']);
Route::post('/', ['as' => 'crm', 'uses' => 'CrmController@store']);
/**
 * Управление клиентами
 */
Route::get('/clients/{client}', 'CrmController@ClientPage')->name('CrmPage');
Route::post('/clients/{client}', ['as' => 'CrmPageUpdate', 'uses' => 'CrmController@update']);
Route::delete('/clients/{client}', 'CrmController@remove')->name('DeleteClient');
/**
 * Управление достапами
 */
Route::post('/clients/{client}/access', ['as' => 'AccessUpdate', 'uses' => 'CrmController@access']);

/**
 * Управление задачами
 */
Route::post('/clients/{client}/task/create', ['as' => 'TaskAction', 'uses' => 'TaskController@create']);
Route::post('/clients/{client}/task/{task}', ['as' => 'TaskUpdate', 'uses' => 'TaskController@update']);

/**
 * Управление оплатами
 */
Route::post('/clients/{client}/payment/create', ['as' => 'PaymentCreate', 'uses' => 'PaymentController@create']);
Route::post('/clients/{client}/payment/{payment}', ['as' => 'PaymentUpdate', 'uses' => 'PaymentController@update']);

/**
 * Управление файлами
 */
Route::post('/upload/{client}', 'TaskFilesController@upload')->name('ClientUpload');
Route::post('/upload/{upload}/update', 'TaskFilesController@update')->name('UploadUpdate');
Route::get('/clients/{client}/download', 'TaskFilesController@download')->name('Download');

/**
 * Профиль
 */
Route::get('/profile', 'UserProfile@index')->name('Profile');
Route::post('/profile', 'UserProfile@update');
Route::post('/profile/photo', 'UserProfile@upload')->name('UploadPhoto');
/**
 * Общая информация
 */
Route::get('about', 'AboutController@index')->name('About');
/**
 * Управление пользователями
 */
Route::get('/users', 'UsersController@index')->name('Users');
Route::post('/users/remove/{user}', 'UsersController@remove')->name('UserRemove');
Route::post('/users/create', 'UsersController@create')->name('UserCreate');

Route::get('login', ['as' => 'login', function () {
    return view('crm.auth', ['pagetitle' => 'Авторизация']);
}]);

Route::post('login', 'Auth\LoginController@login');

Route::get('test/{file}', 'AboutController@test');
Route::get('test', function () {
    phpinfo();
});

Route::get('test_chat', 'ClentChatController@test');