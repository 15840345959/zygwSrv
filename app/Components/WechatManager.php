<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use Illuminate\Support\Facades\Log;
use Qiniu\Auth;

class WechatManager
{
    //服务号，根据openid获取用户信息
    public static function getUserInfoByFWHOpenId($fwh_openid)
    {
        Log::info("getUserInfoByFWHOpenId fwh_openid:" . $fwh_openid);
        $app = app('wechat.official_account');
        $userInfo = $app->user->get($fwh_openid);
        return $userInfo;
    }


    //发送模板消息
    /*
     *  {{first.DATA}}
        审核类型：{{keyword1.DATA}}
        审核结果：{{keyword2.DATA}}
        审核时间：{{keyword3.DATA}}
        {{remark.DATA}}
        在发送时，需要将内容中的参数（{{.DATA}}内为参数）赋值替换为需要的信息
     *
     *
     */
    public static function sendTemplateMessage($fwh_openid, $template_id, $data)
    {
        $app = app('wechat.official_account');
        $app->template_message->send([
            'touser' => $fwh_openid,
            'template_id' => $template_id,
            'url' => '',
            'data' => $data,
        ]);
        $response = $app->server->serve();
        return $response;
    }
}