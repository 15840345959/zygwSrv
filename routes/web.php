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

    //业务概览
    Route::any('/stmt/index', 'Admin\StmtController@index');    //首页
    Route::any('/stmt/export', 'Admin\StmtController@export');  //导出数据

    //错误页面
    Route::get('/error/500', ['as' => 'error', 'uses' => 'Admin\IndexController@error']);  //错误页面

    //管理员管理
    Route::any('/admin/index', 'Admin\AdminController@index');  //管理员管理首页
    Route::get('/admin/setStatus/{id}', 'Admin\AdminController@setStatus');  //设置管理员状态
    Route::get('/admin/edit', 'Admin\AdminController@edit');  //新建或编辑管理员
    Route::post('/admin/edit', 'Admin\AdminController@editPost');  //新建或编辑管理员
    Route::get('/admin/editPassword', 'Admin\AdminController@editPassword');  //修改个人密码get
    Route::post('/admin/editPassword', 'Admin\AdminController@editPasswordPost');  //修改个人密码post

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

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //楼盘标签管理
    Route::any('/house/houseLabel/index', 'Admin\House\HouseLabelController@index');  //楼盘标签管理
    Route::get('/house/houseLabel/edit', 'Admin\House\HouseLabelController@edit');  //楼盘标签管理添加、编辑-get
    Route::post('/house/houseLabel/edit', 'Admin\House\HouseLabelController@editPost');  //楼盘标签管理添加、编辑-post
    Route::get('/house/houseLabel/setStatus/{id}', 'Admin\House\HouseLabelController@setStatus');  //设置楼盘标签状态

    //楼盘类型管理
    Route::any('/house/houseType/index', 'Admin\House\HouseTypeController@index');  //楼盘类型管理
    Route::get('/house/houseType/edit', 'Admin\House\HouseTypeController@edit');  //楼盘类型管理添加、编辑-get
    Route::post('/house/houseType/edit', 'Admin\House\HouseTypeController@editPost');  //楼盘类型管理添加、编辑-post
    Route::get('/house/houseType/setStatus/{id}', 'Admin\House\HouseTypeController@setStatus');  //设置楼盘类型状态

    //楼盘区域管理
    Route::any('/house/houseArea/index', 'Admin\House\HouseAreaController@index');  //楼盘区域管理
    Route::get('/house/houseArea/edit', 'Admin\House\HouseAreaController@edit');  //楼盘区域管理添加、编辑-get
    Route::post('/house/houseArea/edit', 'Admin\House\HouseAreaController@editPost');  //楼盘区域管理添加、编辑-post
    Route::get('/house/houseArea/setStatus/{id}', 'Admin\House\HouseAreaController@setStatus');  //设置楼盘区域状态

    //项目楼盘联系人相关
    Route::any('/house/houseContact/index', 'Admin\House\HouseContactController@index');//根据楼盘id获取相应的楼盘联系人
    Route::get('/house/houseContact/edit', 'Admin\House\HouseContactController@edit');//新建或编辑楼盘联系人
    Route::post('/house/houseContact/edit', 'Admin\House\HouseContactController@editPost');//新建或编辑楼盘联系人
    Route::get('/house/houseContact/setStatus/{id}', 'Admin\House\HouseContactController@setStatus');  //设置楼盘联系人状态

    //楼盘管理
    Route::any('/house/house/index', 'Admin\House\HouseController@index');  //楼盘管理
    Route::get('/house/house/edit', 'Admin\House\HouseController@edit');  //楼盘管理添加、编辑-get
    Route::post('/house/house/edit', 'Admin\House\HouseController@editPost');  //楼盘管理添加、编辑-post
    Route::get('/house/house/setStatus/{id}', 'Admin\House\HouseController@setStatus');  //设置楼盘状态

    //户型管理
    Route::get('/house/huxing/edit', 'Admin\House\HuxingController@edit');  //户型管理添加、编辑-get
    Route::post('/house/huxing/edit', 'Admin\House\HuxingController@editPost');  //户型管理添加、编辑-post
    Route::get('/house/huxing/setStatus/{id}', 'Admin\House\HuxingController@setStatus');  //设置户型状态
    Route::get('/house/huxing/editYongjin', 'Admin\House\HuxingController@editYongjin');//新建或编辑产品的佣金
    Route::post('/house/huxing/editYongjin', 'Admin\House\HuxingController@editYongjinPost');//新建或编辑产品的佣金
    Route::get('/house/huxingYongjinRecord/index', 'Admin\House\HuxingYongjinRecordController@index');//户型佣金记录首页

    //项目户型样式相关
    Route::any('/house/huxingStyle/index', 'Admin\House\HuxingStyleController@index');//根据户型样式id获取相应的户型样式
    Route::get('/house/huxingStyle/edit', 'Admin\House\HuxingStyleController@edit');//新建或编辑户型样式
    Route::post('/house/huxingStyle/edit', 'Admin\House\HuxingStyleController@editPost');//新建或编辑户型样式
    Route::get('/house/huxingStyle/setStatus/{id}', 'Admin\House\HuxingStyleController@setStatus');  //设置户型样式状态

    //项目楼盘客户相关
    Route::any('/house/houseClient/index', 'Admin\House\HouseClientController@index');//根据楼盘客户id获取相应的楼盘客户
    Route::get('/house/houseClient/edit', 'Admin\House\HouseClientController@edit');//新建或编辑楼盘客户
    Route::post('/house/houseClient/edit', 'Admin\House\HouseClientController@editPost');//新建或编辑楼盘客户
    Route::get('/house/houseClient/del/{id}', 'Admin\House\HouseClientController@del');  //删除楼盘客户

    //楼盘详情
    Route::post('/house/detail/edit', 'Admin\House\HouseDetailController@editPost');  //楼盘详情管理添加、编辑-post

    //客户管理
    Route::any('/client/index', 'Admin\ClientController@index');  //客户管理首页


    //置业顾问
    Route::get('/house/zygw/edit', 'Admin\House\ZYGWController@edit');  //置业顾问管理添加、编辑-get
    Route::post('/house/zygw/edit', 'Admin\House\ZYGWController@editPost');  //置业顾问管理添加、编辑-post
    Route::get('/house/zygw/setStatus/{id}', 'Admin\House\ZYGWController@setStatus');  //设置置业顾问状态

    //规则编辑
    Route::any('/tw/index', 'Admin\TWController@index');  //规则首页
    Route::get('/tw/edit', 'Admin\TWController@edit');    //规则编辑管理添加、编辑-get
    Route::post('/tw/edit', 'Admin\TWController@editPost');    //规则编辑管理添加、编辑-post


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //用户相关管理-中介+案场负责人
    //中介
    Route::any('/user/userZJ/index', 'Admin\User\UserZJController@index');  //中介首页

    //案场负责人
    Route::any('/user/userACFZR/index', 'Admin\User\UserACFZRController@index');  //案场负责人

    //签到
    Route::any('/user/userQD/index', 'Admin\User\UserQDController@index');  //签到明细

    //中介申请成为案场负责人管理
    Route::any('/user/userUp/index', 'Admin\User\UserUpController@index');  //案场升级
    Route::get('/user/userUp/setStatus/{id}', 'Admin\User\UserUpController@setStatus');  //设置升级状态

    Route::any('/user/recommInfo/index', 'Admin\User\RecommInfoController@index');        //推荐相关

    Route::get('/user/setStatus/{id}', 'Admin\User\UserController@setStatus');  //设置用户状态
    Route::get('/user/setRole/{id}', 'Admin\User\UserController@setRole');  //设置角色

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////
    //项目购买目的相关
    Route::any('/baobei/baobeiBuyPurpose/index', 'Admin\Baobei\BaobeiBuyPurposeController@index');//根据购买目的id获取相应的购买目的
    Route::get('/baobei/baobeiBuyPurpose/edit', 'Admin\Baobei\BaobeiBuyPurposeController@edit');//新建或编辑购买目的
    Route::post('/baobei/baobeiBuyPurpose/edit', 'Admin\Baobei\BaobeiBuyPurposeController@editPost');//新建或编辑购买目的
    Route::get('/baobei/baobeiBuyPurpose/setStatus/{id}', 'Admin\Baobei\BaobeiBuyPurposeController@setStatus');  //设置购买目的状态

    Route::any('/baobei/baobeiKnowWay/index', 'Admin\Baobei\BaobeiKnowWayController@index');//根据购买目的id获取相应的购买目的
    Route::get('/baobei/baobeiKnowWay/edit', 'Admin\Baobei\BaobeiKnowWayController@edit');//新建或编辑购买目的
    Route::post('/baobei/baobeiKnowWay/edit', 'Admin\Baobei\BaobeiKnowWayController@editPost');//新建或编辑购买目的
    Route::get('/baobei/baobeiKnowWay/setStatus/{id}', 'Admin\Baobei\BaobeiKnowWayController@setStatus');  //设置购买目的状态

    Route::any('/baobei/baobeiPayWay/index', 'Admin\Baobei\BaobeiPayWayController@index');//根据购买目的id获取相应的购买目的
    Route::get('/baobei/baobeiPayWay/edit', 'Admin\Baobei\BaobeiPayWayController@edit');//新建或编辑购买目的
    Route::post('/baobei/baobeiPayWay/edit', 'Admin\Baobei\BaobeiPayWayController@editPost');//新建或编辑购买目的
    Route::get('/baobei/baobeiPayWay/setStatus/{id}', 'Admin\Baobei\BaobeiPayWayController@setStatus');  //设置购买目的状态

    Route::any('/baobei/baobeiClientCare/index', 'Admin\Baobei\BaobeiClientCareController@index');//根据购买目的id获取相应的购买目的
    Route::get('/baobei/baobeiClientCare/edit', 'Admin\Baobei\BaobeiClientCareController@edit');//新建或编辑购买目的
    Route::post('/baobei/baobeiClientCare/edit', 'Admin\Baobei\BaobeiClientCareController@editPost');//新建或编辑购买目的
    Route::get('/baobei/baobeiClientCare/setStatus/{id}', 'Admin\Baobei\BaobeiClientCareController@setStatus');  //设置购买目的状态

    Route::any('/baobei/baobei/index', 'Admin\Baobei\BaobeiController@index');  //报备首页
    Route::get('/baobei/baobei/info', 'Admin\Baobei\BaobeiController@info');  //报备信息
    Route::get('/baobei/baobei/payZhongjie', 'Admin\Baobei\BaobeiController@payZhongjie');  //中介结算
    Route::post('/baobei/baobei/payZhongjie', 'Admin\Baobei\BaobeiController@payZhongjiePost');  //中介结算-post
    Route::get('/baobei/baobei/resetDealInfo', 'Admin\Baobei\BaobeiController@resetDealInfo');  //重设成交信息
    Route::post('/baobei/baobei/resetDealInfo', 'Admin\Baobei\BaobeiController@resetDealInfoPost');  //重设成交信息-post

    ////////////////////////////////////////////////////////////////////////////////////////////


    //商品相关
    Route::any('/jifen/goods/index', 'Admin\Jifen\GoodsController@index');//根据商品id获取相应的商品
    Route::get('/jifen/goods/edit', 'Admin\Jifen\GoodsController@edit');//新建或编辑商品
    Route::post('/jifen/goods/edit', 'Admin\Jifen\GoodsController@editPost');//新建或编辑商品
    Route::get('/jifen/goods/setStatus/{id}', 'Admin\Jifen\GoodsController@setStatus');  //设置商品状态

    //商品兑换订单
    Route::any('/jifen/goodsExchange/index', 'Admin\Jifen\GoodsExchangeController@index');  //获取兑换订单
    Route::get('/jifen/goodsExchange/setStatus/{id}', 'Admin\Jifen\GoodsExchangeController@setStatus');  //设置订单状态

    //积分变更记录
    Route::any('/jifen/jifenChangeRecord/index', 'Admin\Jifen\JifenChangeRecordController@index');  //积分变更记录-首页

});






