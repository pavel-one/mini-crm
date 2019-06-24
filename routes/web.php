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
Route::get('/clients/{client}/api/getLog', 'CrmController@getLog')->name('CrmPageLog');
Route::get('/clients/{client}/filesDownload/{filename}', 'CrmController@DownloadFile')->name('CrmDownloadFile');

Route::post('/clients/{client}/photoUpdate', 'CrmController@UpdatePhoto')->name('CrmUpdatePhoto');
Route::post('/clients/{client}/filesUpdate', 'CrmController@UpdateFiles')->name('CrmUpdateFiles');
Route::post('/clients/{client}/filesRemove/{filename}', 'CrmController@RemoveFile')->name('CrmRemoveFile');
Route::post('/clients/{client}', ['as' => 'CrmPageUpdate', 'uses' => 'CrmController@update']);
Route::post('/clients/{client}/actions', 'CrmController@actions')->name('ClientActions');

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
 * Управление чатом
 */
Route::get('/clients/{client}/chat/getAll', ['as' => 'GetAll', 'uses' => 'ClentChatController@all']);

Route::post('/clients/{client}/chat/create', ['as' => 'NewMessage', 'uses' => 'ClentChatController@create']);
/**
 * Управление оплатами
 */
Route::post('/clients/{client}/payment/create', ['as' => 'PaymentCreate', 'uses' => 'PaymentController@create']);
Route::post('/clients/{client}/payment/{payment}', ['as' => 'PaymentUpdate', 'uses' => 'PaymentController@update']);

/**
 * Управление файлами
 */
Route::get('/clients/{client}/download', 'TaskFilesController@download')->name('Download');

Route::post('/upload/{client}', 'TaskFilesController@upload')->name('ClientUpload');
Route::post('/upload/{upload}/update', 'TaskFilesController@update')->name('UploadUpdate');

/**
 * Профиль
 */
Route::get('/profile', 'UserProfile@index')->name('Profile');
Route::get('/profile/getcount', 'UserProfile@getcount')->name('checkNew');
Route::get('/profile/messages/{topic_id}', 'UserProfile@getMessages')->name('LoadTopic');
Route::get('/profile/messages/download/{filename}', 'UserProfile@msgDownloadFile')->name('msgDownload');
Route::get('/profile/{nick}', 'UserProfile@profile')->name('ProfilePage');
Route::get('/profile/{nick}/api/getLog', 'UserProfile@getLog')->name('ProfilePageLog');

Route::post('/profile', 'UserProfile@update');
Route::post('/profile/messages/{topic_id}', 'UserProfile@newMessage')->name('NewMessageLk');
Route::post('/profile/{nick}/send', 'UserProfile@send')->name('ProfileMessageSend');
Route::post('/profile/photo', 'UserProfile@upload')->name('UploadPhoto');
/**
 * Общая информация
 */
Route::get('about', 'AboutController@index')->name('About');
Route::get('about/api/getLog', 'AboutController@getLog')->name('AboutPageLog');

/**
 * Управление пользователями
 */
Route::get('/users', 'UsersController@index')->name('Users');

Route::post('/users/remove/{user}', 'UsersController@remove')->name('UserRemove');
Route::post('/users/create', 'UsersController@create')->name('UserCreate');

Route::get('login', ['as' => 'login', function () {
    return view('crm.auth', ['pagetitle' => 'Авторизация']);
}]);

Route::post('alert', 'TelegramController@alert')->name('alert');
Route::post('telega', 'TelegramController@hook')->name('TgHook');
//Route::get('telega', 'TestController@test')->name('TgHook');
Route::get('test', 'TelegramController@test');

Route::post('login', 'Auth\LoginController@login');
