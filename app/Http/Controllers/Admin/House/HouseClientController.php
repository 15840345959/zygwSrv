<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\ADManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Components\HouseManager;
use App\Components\HouseClientManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\Doctor;
use App\Models\HouseClient;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class HouseClientController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //配置参数
        $house_id = null;
        $search_word = null;

        if (array_key_exists('house_id', $data) && !Utils::isObjNull($data['house_id'])) {
            $house_id = $data['house_id'];
        }
        $house = HouseManager::getById($house_id);

        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }

        $con_arr = array(
            'house_id' => $house_id,
            'search_word' => $search_word
        );
        $houseClients = HouseClientManager::getListByCon($con_arr, true);

        foreach ($houseClients as $houseClient) {
            $houseClient = HouseClientManager::getInfoByLevel($houseClient, '01');
        }

        $upload_token = QNManager::uploadToken();
        return view('admin.house.houseClient.index', ['admin' => $admin, 'datas' => $houseClients, 'house' => $house,
            'con_arr' => $con_arr, 'upload_token' => $upload_token]);
    }


    //删除楼盘
    public function del(Request $request, $id)
    {
        //房产商客户id非数字
        if (!is_numeric($id)) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数广告id$id']);
        }
        $houseClient = HouseClient::find($id);
        $houseClient->delete();
        // $houseClient =$request->all()['search'];

        return ApiResponse::makeResponse(true, "删除成功", ApiResponse::SUCCESS_CODE);
    }


    /*
    * 添加、编辑图-get
    *
    * By mtt
    *
    * 2018-4-9
    */
    public function edit(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $house = HouseManager::getById($data['house_id']);

        return view('admin.house.houseClient.edit', ['admin' => $admin, 'house' => $house, 'upload_token' => $upload_token]);
    }


    //新建或编辑房产商客户->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'phonenums' => 'required',
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $house_client_arr = explode(",", $data['phonenums']);
        foreach ($house_client_arr as $item) {
            $houseClient = new HouseClient();
            $houseClient->house_id = $data['house_id'];
            $houseClient->admin_id = $admin->id;
            $houseClient->phonenum = $item;
            $houseClient->save();
        }
        return ApiResponse::makeResponse(true, "导入成功", ApiResponse::SUCCESS_CODE);
    }

}