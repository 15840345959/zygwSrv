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
use App\Components\HouseContactManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Components\XJManager;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\House;
use App\Models\Doctor;
use App\Models\HouseContact;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class HouseContactController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //配置参数
        $search_word = null;
        $status = null;

        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }

        $con_arr = array(
            'search_word' => $search_word,
            'status' => $status
        );
        $houseContacts = HouseContactManager::getListByCon($con_arr, true);

        foreach ($houseContacts as $houseContact) {
            $houseContact = HouseContactManager::getInfoByLevel($houseContact, '0');
        }

        $upload_token = QNManager::uploadToken();
        return view('admin.house.houseContact.index', ['admin' => $admin, 'datas' => $houseContacts
            , 'con_arr' => $con_arr, 'upload_token' => $upload_token]);
    }


    //设置状态
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }
        $houseContact = HouseContactManager::getById($data['id']);
        $houseContact->status = $data['status'];
        $houseContact->save();
        return ApiResponse::makeResponse(true, $houseContact, ApiResponse::SUCCESS_CODE);
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
        $houseContact = new HouseContact();
        if (array_key_exists('id', $data)) {
            $houseContact = HouseContactManager::getById($data['id']);
        }
        return view('admin.house.houseContact.edit', ['admin' => $admin, 'data' => $houseContact, 'upload_token' => $upload_token]);
    }


    //新建或编辑房产商客户->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
//        dd($data);
        $houseContact = new HouseContact();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $houseContact = HouseContactManager::getById($data['id']);
        }
        $houseContact = HouseContactManager::setInfo($houseContact, $data);
        $houseContact->admin_id = $admin->id;
        $houseContact->save();

        return ApiResponse::makeResponse(true, "添加成功", ApiResponse::SUCCESS_CODE);
    }
}