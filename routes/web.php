<?php

use App\Http\Middleware\FiveChMiddleware;
use App\Http\Middleware\ResponseMiddleware;

//ログインとか
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//トップページ
Route::get('/', function () {
    return view('main');
});

//ユーザーページとセッティングページ
Route::get('user/{user_id}', 'UserController@user_page');
Route::get('settings', 'UserController@settings');

Route::post('settings/update', 'UserController@settings_update');

//5ch互換部分
Route::get('test/bbs.cgi', 'FiveChController@write_ready');
Route::post('test/bbs.cgi', 'FiveChController@write')
->middleware(FiveChMiddleware::class);

//JSON

//板のデータをJSONで返す
Route::get('json/{board_key}', 'JsonController@board_read');
Route::get('json/{board_key}/hot/', 'JsonController@board_read');
Route::get('json/{board_key}/new/', 'JsonController@board_read_new');
Route::get('json/{board_key}/top/', 'JsonController@board_read_top');

//スレッドののデータをJSONで返す
Route::get('json/{board_key}/{thread_key}', 'JsonController@thread_read');
Route::get('json/{board_key}/{thread_key}/{res_select}', 'JsonController@res_read');

//5chのurlをツガレコミンのURLに変換する
Route::get('/test/read.cgi/{board_key}/', 'FiveChController@read_cgi_board');
Route::get('/test/read.cgi/{board_key}/{thread_key}/', 'FiveChController@read_cgi_thread');
Route::get('/test/read.cgi/{board_key}/{thread_key}/{res_number}', 'FiveChController@read_cgi_res');

//ツガレコミン独自部分

//板を開いた場合
Route::get('r/{board_key}', 'TsugarecominController@board_read');
Route::get('r/{board_key}/hot/', 'TsugarecominController@board_read');
Route::get('r/{board_key}/new/', 'TsugarecominController@board_read_new');
Route::get('r/{board_key}/top/', 'TsugarecominController@board_read_top');

//Upvoteをする場合
Route::post('upvote/{board_key}/{thread_key}', 'TsugarecominController@thread_upvote');

//スレッドを開いた場合
Route::get('r/{board_key}/{thread_key}/all', 'TsugarecominController@thread_read')
->middleware(ResponseMiddleware::class);
Route::get('r/{board_key}/{thread_key}/{res_select}', 'TsugarecominController@res_read')
->middleware(ResponseMiddleware::class);

Route::get('r/{board_key}/{thread_key}/id/{id}', 'TsugarecominController@thread_id_read');

//いいねをした場合
Route::post('like/{board_key}/{thread_key}/{comment_key}', 'TsugarecominController@res_like');