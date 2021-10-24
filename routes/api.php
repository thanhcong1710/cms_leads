<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('menu', 'MenuController@index');

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('register', 'AuthController@register'); 
    Route::post('single-sign-on', 'AuthController@singleSignOn');
    Route::get('switch-system', 'AuthController@switchSystem');
    Route::get('webhook/voip24h', 'VoipController@webhook');
    Route::get('socket_io/test', 'VoipController@socketIo');
    Route::get('tools/download_record', 'ToolsController@downloadReCord');

    Route::group(['middleware' => 'admin'], function ($router) {
        Route::resource('users', 'UsersController')->except( ['create', 'store'] );
        Route::post('/users/add', 'UsersController@add');
        Route::post('/users/list', 'UsersController@list');
        Route::post('/users/update/{id}', 'UsersController@update');

        Route::prefix('menu/menu')->group(function () { 
            Route::get('/',         'MenuEditController@index')->name('menu.menu.index');
            Route::get('/create',   'MenuEditController@create')->name('menu.menu.create');
            Route::post('/store',   'MenuEditController@store')->name('menu.menu.store');
            Route::get('/edit',     'MenuEditController@edit')->name('menu.menu.edit');
            Route::post('/update',  'MenuEditController@update')->name('menu.menu.update');
            Route::get('/delete',   'MenuEditController@delete')->name('menu.menu.delete');
        });
        Route::prefix('menu/element')->group(function () { 
            Route::get('/',             'MenuElementController@index')->name('menu.index');
            Route::get('/move-up',      'MenuElementController@moveUp')->name('menu.up');
            Route::get('/move-down',    'MenuElementController@moveDown')->name('menu.down');
            Route::get('/create',       'MenuElementController@create')->name('menu.create');
            Route::post('/store',       'MenuElementController@store')->name('menu.store');
            Route::get('/get-parents',  'MenuElementController@getParents');
            Route::get('/edit',         'MenuElementController@edit')->name('menu.edit');
            Route::post('/update',      'MenuElementController@update')->name('menu.update');
            Route::get('/show',         'MenuElementController@show')->name('menu.show');
            Route::get('/delete',       'MenuElementController@delete')->name('menu.delete');
        });
        Route::prefix('media')->group(function ($router) {
            Route::get('/',                 'MediaController@index')->name('media.folder.index');
            Route::get('/folder/store',     'MediaController@folderAdd')->name('media.folder.add');
            Route::post('/folder/update',   'MediaController@folderUpdate')->name('media.folder.update');
            Route::get('/folder',           'MediaController@folder')->name('media.folder');
            Route::post('/folder/move',     'MediaController@folderMove')->name('media.folder.move');
            Route::post('/folder/delete',   'MediaController@folderDelete')->name('media.folder.delete');;

            Route::post('/file/store',      'MediaController@fileAdd')->name('media.file.add');
            Route::get('/file',             'MediaController@file');
            Route::post('/file/delete',     'MediaController@fileDelete')->name('media.file.delete');
            Route::post('/file/update',     'MediaController@fileUpdate')->name('media.file.update');
            Route::post('/file/move',       'MediaController@fileMove')->name('media.file.move');
            Route::post('/file/cropp',      'MediaController@cropp');
            Route::get('/file/copy',        'MediaController@fileCopy')->name('media.file.copy');

            Route::get('/file/download',    'MediaController@fileDownload');
        });

        Route::resource('roles',        'RolesController');
        Route::get('/roles/move/move-up',      'RolesController@moveUp')->name('roles.up');
        Route::get('/roles/move/move-down',    'RolesController@moveDown')->name('roles.down');
        Route::get('/language/{language}', 'LangController@setLanguage');
        Route::post('/parents/list', 'ParentsController@list');
        Route::post('/parents/add', 'ParentsController@add');
        Route::post('/parents/update/{parent_id}', 'ParentsController@update');
        Route::get('/parents/detail/{parent_id}', 'ParentsController@detail');
        Route::post('/parents/assign', 'ParentsController@assign');
        Route::post('/parents/assign_list', 'ParentsController@assignList');
        Route::post('/parents/change_status', 'ParentsController@changeStaus');
        Route::post('/parents/validate_phone', 'ParentsController@validatePhone');
        Route::post('/parents/validate_c2c_phone', 'ParentsController@validateC2CPhone');
        Route::post('/parents/overwrite', 'ParentsController@overwrite');
        Route::get('/parents/show/{parent_id}', 'ParentsController@show');
        Route::get('/parents/get_logs/{parent_id}', 'ParentsController@getLogs');
        Route::get('/parents/make_to_call/{parent_id}', 'ParentsController@makeToCall');
        Route::get('parents/get_info_by_phone/{phone}', 'ParentsController@getInfoByPhone');
        Route::post('/parents/send_sms', 'ParentsController@sendSms');
        Route::post('/parents/update_next_care_date', 'ParentsController@updateNextCareDate');

        Route::get('/provinces', 'SystemInfoController@getAllProvices');
        Route::get('/provinces/{province_id}/districts', 'SystemInfoController@getDistrictsByProvice');
        Route::get('/jobs', 'SystemInfoController@getAllJobs');
        Route::get('/sources', 'SystemInfoController@getAllSources');
        Route::get('/methods', 'SystemInfoController@getAllMethods');
        Route::get('/branches', 'SystemInfoController@getAllBranches');
        Route::get('get/{province_id}/{district_id}/{school_level}/schools', 'SystemInfoController@getSchools');
        Route::post('/care/add', 'ParentCareController@add');
        Route::get('/care/get_all_data/{parent_id}', 'ParentCareController@getAllDataByParent');
        Route::get('/care/get_info_call/{care_id}', 'ParentCareController@getInfoCall');
        Route::post('/care/udpate_note', 'ParentCareController@updateNoteCare');
        Route::post('/students/add', 'StudentsController@add');
        Route::get('/students/get_all_data/{parent_id}', 'StudentsController@getAllDataByParent');
        Route::post('/imports/list', 'ImportsController@list');
        Route::post('/imports/upload', 'ImportsController@upload');
        Route::post('/imports/assign', 'ImportsController@assign');
        Route::get('/user/get-users-manager', 'UsersController@getUserAssgin');
        
    });
});

