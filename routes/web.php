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


//登录
Route::get('/admin/login', 'Admin\LoginController@login');        //登录
Route::post('/admin/login', 'Admin\LoginController@loginPost');   //post登录请求
Route::get('/admin/loginout', 'Admin\LoginController@loginout');  //注销

Route::group(['prefix' => 'admin', 'middleware' => ['admin.login']], function () {

    //首页
    Route::get('/', 'Admin\IndexController@index');       //首页
    Route::get('/index', 'Admin\IndexController@index');  //首页

    //错误页面
    Route::get('/error/500', ['as' => 'error', 'uses' => 'Admin\IndexController@error']);  //错误页面

    //管理员管理
    Route::any('/admin/index', 'Admin\AdminController@index');  //管理员管理首页
    Route::get('/admin/setStatus/{id}', 'Admin\AdminController@setStatus');  //设置管理员状态
    Route::get('/admin/edit', 'Admin\AdminController@edit');  //新建或编辑管理员
    Route::post('/admin/edit', 'Admin\AdminController@editPost');  //新建或编辑管理员
    Route::get('/admin/editMySelf', ['as' => 'editMySelf', 'uses' => 'Admin\AdminController@editMySelf']);  //修改个人资料get
    Route::post('/admin/editMySelf', 'Admin\AdminController@editMySelfPost');  //修改个人资料post

    //轮播图管理
    Route::any('/ad/index', 'Admin\ADController@index');  //轮播图管理
    Route::get('/ad/edit', 'Admin\ADController@edit');  //轮播图管理添加、编辑-get
    Route::post('/ad/edit', 'Admin\ADController@editPost');  //轮播图管理添加、编辑-post
    Route::get('/ad/setStatus/{id}', 'Admin\ADController@setStatus');  //设置轮播图状态
    Route::get('/ad/del/{id}', 'Admin\ADController@del');  //删除广告图

    //系统配置信息相关
    Route::get('/system/index', 'Admin\SystemController@index');  //系统配置首页信息
    Route::get('/system/edit', 'Admin\SystemController@edit');  //设置系统页面
    Route::post('/system/edit', 'Admin\SystemController@editPost');  //设置系统页面-post

});






