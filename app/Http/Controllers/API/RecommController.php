<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\ADManager;
use App\Components\HomeManager;
use App\Components\RecommInfoManager;
use App\Components\SendMessageManager;
use App\Components\SystemManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\JifenChangeRecord;
use App\Models\RecommInfo;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class RecommController extends Controller
{

    /*
     * 推荐接口
     *
     * By TerryQi
     *
     * 2018-02-25
     */
    public function recommUser(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
            're_user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //推荐用户信息是否存在
        if (!UserManager::getById($data['re_user_id'])) {
            return ApiResponse::makeResponse(false, '推荐人信息不存在', ApiResponse::INNER_ERROR);
        }
        if (RecommInfoManager::isUserHasBeenRecommended($data['user_id'])) {
            return ApiResponse::makeResponse(false, "已经被推荐", ApiResponse::INNER_ERROR);
        }
        //不能互相推荐的逻辑判断
        $re_user = UserManager::getById($data['re_user_id']);
        if ($re_user->re_user_id == $data['user_id']) {   //推荐用户的推荐用户id与被推荐用户的user_id一致，代表在互相推荐
            return ApiResponse::makeResponse(false, "不能互相推荐", ApiResponse::INNER_ERROR);
        }
        //建立推荐关联关系
        $recommInfo = new RecommInfo();
        $recommInfo = RecommInfoManager::setInfo($recommInfo, $data);
        $recommInfo->save();
        //修改被推荐用户的推荐人信息
        $user = UserManager::getByIdWithToken($data['user_id']);
        $user->re_user_id = $data['re_user_id'];
        $user->save();
        //增加推荐用户积分
        $systemInfo = SystemManager::getCurrentInfo();   //系统配置信息
        $user = UserManager::getByIdWithToken($data['re_user_id']);  //推荐用户信息
        $user->jifen = $user->jifen + $systemInfo->tj_jifen;
        $user->save();
        //写入记录
        $jifen_change_record = new JifenChangeRecord();
        $jifen_change_record->user_id = $user->id;
        $jifen_change_record->jifen = $systemInfo->tj_jifen;
        $jifen_change_record->record = "推荐成功奖励";
        $jifen_change_record->save();
        //发送消息
        $message_content = [
            'keyword1' => '积分增加',
            'keyword2' => '推荐成功奖励',
            'keyword3' => $jifen_change_record->jifen,
        ];
        SendMessageManager::sendMessage($user->id, SendMessageManager::JIFEN_CHANGE, $message_content);
        return ApiResponse::makeResponse(true, $recommInfo, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据推荐用户id获取被推荐人信息
     *
     * BY TerryQi
     *
     * 2018-02-25
     *
     */
    public function getListByCon(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $re_user_id = $data['user_id'];
        $recommInfos = RecommInfoManager::getListByCon(["re_user_id" => $re_user_id], false);
        foreach ($recommInfos as $recommInfo) {
            $recommInfo = RecommInfoManager::getInfoByLevel($recommInfo, '0');
        }
        return ApiResponse::makeResponse(true, $recommInfos, ApiResponse::SUCCESS_CODE);
    }

}