<?php

use App\Http\Middleware\fivechMiddleware;

Route::get('/', function () {
    return view('main');
});

//5ch互換部分
Route::get('test/bbs.cgi', 'FiveChController@write_ready');
Route::post('test/bbs.cgi', 'FiveChController@write')
->middleware(FiveChMiddleware::class);

//JSONを吐く
Route::get('json/{board_key}', 'JsonController@board_read');
Route::get('json/{board_key}/hot/', 'JsonController@board_read');
Route::get('json/{board_key}/new/', 'JsonController@board_read_new');
Route::get('json/{board_key}/top/', 'JsonController@board_read_top');

Route::get('json/{board_key}/{thread_key}', 'JsonController@thread_read');
Route::get('json/{board_key}/{thread_key}/{res_select}', 'JsonController@res_read');

//5chのurlをツガレコミンのURLに変換する
Route::get('/test/read.cgi/{board_key}/', 'FiveChController@read_cgi_board');
Route::get('/test/read.cgi/{board_key}/{thread_key}/', 'FiveChController@read_cgi_thread');
Route::get('/test/read.cgi/{board_key}/{thread_key}/{res_number}', 'FiveChController@read_cgi_res');

//ツガレコミン独自部分
Route::get('r/{board_key}', 'TsugarecominController@board_read');
Route::get('r/{board_key}/hot/', 'TsugarecominController@board_read');
Route::get('r/{board_key}/new/', 'TsugarecominController@board_read_new');
Route::get('r/{board_key}/top/', 'TsugarecominController@board_read_top');


Route::get('r/{board_key}/{thread_key}', 'TsugarecominController@thread_read');
Route::get('r/{board_key}/{thread_key}/{res_select}', 'TsugarecominController@res_read');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
