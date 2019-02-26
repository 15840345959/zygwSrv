<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\ADManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\SystemManager;
use App\Components\SystemRecordManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Libs\CommonUtils;
use App\Models\AD;
use App\Models\Doctor;
use App\Models\System;
use App\Models\SystemRecord;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class SystemController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $systemInfo = SystemManager::getCurrentInfo();
        $systemRecords = SystemRecordManager::getListByCon([], false);
        foreach ($systemRecords as $systemRecord) {
            $systemRecord = SystemRecordManager::getInfoByLevel($systemRecord, 0);
        }
        return view('admin.system.index', ['admin' => $admin, 'data' => $systemInfo, 'systemRecords' => $systemRecords]);
    }


    //新建或编辑系统配置-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //获取系统配置
        $system = SystemManager::getCurrentInfo();

        return view('admin.system.edit', ['admin' => $admin, 'data' => $system]);
    }

    //新建或编辑系统配置->post
    public function editPost(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'qd_jifen' => 'required',
            'tj_jifen' => 'required',
            'df_jifen' => 'required',
            'cj_jifen' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        //保存设置的系统配置
        $system = SystemManager::getCurrentInfo();
        $system = SystemManager::setInfo($system, $data);
        $system->save();
        //设置记录
        $systemRecord = new SystemRecord();
        $systemRecord->admin_id = $admin->id;
        $systemRecord->desc = "系统配置 签到积分：" . $data['qd_jifen'] . " 推荐积分："
            . $data['tj_jifen'] . " 到访积分：" . $data['df_jifen'] . " 成交积分：" . $data['cj_jifen'];
        $systemRecord->save();

        return ApiResponse::makeResponse(true, "修改成功", ApiResponse::SUCCESS_CODE);
    }

}