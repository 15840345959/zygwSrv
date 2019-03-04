<?php

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


Route::group(['prefix' => '', 'middleware' => ['BeforeRequest']], function () {

    //获取七牛token
    Route::get('user/getQiniuToken', 'API\UserController@getQiniuToken');

    //用户相关
    Route::get('user/getById', 'API\UserController@getUserById');    //根据id获取用户信息
    Route::get('user/getByIdWithToken', 'API\UserController@getByIdWithToken')->middleware('CheckToken');  //根据id获取用户信息带token
    Route::get('user/getXCXOpenId', 'API\UserController@getXCXOpenId');//根据code获取openid
    Route::get('user/getUnionId', 'API\UserController@getUnionId');    //根据code获取unionId
    Route::post('user/login', 'API\UserController@login');    //登录/注册
    Route::post('user/updateById', 'API\UserController@updateById')->middleware('CheckToken');//更新用户信息
    Route::post('user/encryptedData', 'API\UserController@encryptedData');//解密encryptedData
    Route::post('user/applyUp', 'API\UserUpController@userUpApply');//中介升级为案场负责接口
    Route::get('user/getMyInfo', 'API\UserController@getMyInfo');//获取个人主页的相关数据
    Route::get('userUp/getListByUserId', 'API\UserUpController@getListByUserId')->middleware('CheckToken');    //根据用户id获取申请列表

    //获取广告图
    Route::get('ad/getListByCon', 'API\ADController@getListByCon');    //获取首页轮播图
    Route::get('ad/getById', 'API\ADController@getADById');     //根据轮播图的id获取相应的信息

    //用户签到-By TerryQi
    Route::post('user/userQDToday', 'API\UserQDController@userQDToday')->middleware('CheckToken', 'CheckStatus');        //用户签到接口
    Route::get('user/getUserQDsByUserId', 'API\UserQDController@getUserQDsByUserId')->middleware('CheckToken');        //根据用户id获取签到列表

    //文章管理
    Route::get('tw/getById', 'API\TWController@getById');//图文
    Route::get('tw/getByType', 'API\TWController@getByType');   //根据图文类型获取相关图文


    //楼盘相关
    Route::get('house/getById', 'API\HouseController@getById');       //根/api/house/searchByCon据id获取楼盘信息
    Route::get('house/getOptions', 'API\HouseController@getOptions');       //获取楼盘相关选项
    Route::get('house/getListByCon', 'API\HouseController@getListByCon');       //根据名称获取楼盘列表

    //楼盘联系人
    Route::get('house/contact/getListByCon', 'API\HouseContactController@getListByCon');       //根据条件获取楼盘联系人列表
    Route::get('house/contact/getById', 'API\HouseContactController@getById');       //根据id获取楼盘联系人信息

    //产品
    Route::get('house/huxing/getById', 'API\HuxingController@getById');       //根据id获取户型信息-调整controller By TerryQi 需要建立HuxingContoller
    Route::get('house/huxing/getListByCon', 'API\HuxingController@getListByCon');       //根据id获取户型信息-调整controller By TerryQi 需要建立HuxingContoller

    //楼盘详情
    Route::get('house/detail/getByHouseId', 'API\HouseDetailController@getByHouseId');       //根/api/house/searchByCon据id获取楼盘信息

    //置业顾问
    Route::get('house/zygw/getListByCon', 'API\ZYTWController@getListByCon');       //根据条件获取顾问列表


    //获取积分商品
    Route::get('goods/getListByCon', 'API\GoodsController@getListByCon');       //获取商品兑换列表
    Route::get('goods/getById', 'API\GoodsController@getById');     //根据id获取商品明细信息
    Route::post('goods/exchange', 'API\GoodsController@exchange')->middleware('CheckToken');     //兑换商品

});

