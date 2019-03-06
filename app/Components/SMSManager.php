<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use Illuminate\Support\Facades\Log;
use Qiniu\Auth;

class SMSManager
{

    // 秒嘀的url基础请求地址
    public static $BASE_URL = "https://api.miaodiyun.com/20150822/";

    // key
    public static $ACCOUNT_SID = "54aaecef86ff460c8765f224f3c4c39d";
    // token
    public static $AUTH_TOKEN = "15fbd5d2124e4e0ba97c252b71aa4b0b";

    // http请求字符编码
    public static $CONTENT_TYPE = "application/x-www-form-urlencoded";

    // http提交数据方式
    public static $ACCEPT = "application/json";

    // 发送验证码
    public static function sendSMSVerification($telphone, $vertify_code)
    {
        // url中{function}/{operation}?部分
        $funAndOperate = "industrySMS/sendSMS";
        $body = self::createBasicAuthData();
//        $body['smsContent'] = "【短信测试】您的验证码是1018，30分钟内有效。by leek";
        // 模板id
        $body['templateid'] = "144098616";
        // 模板中的变量
        $body['param'] = $vertify_code . ",5分钟";
        // 下发验证码的手机号码
        $body['to'] = $telphone;
        $result = self::post($funAndOperate, $body);
        return $result;
    }

    //发送通知
    public static function sendSMS($telphone, $templated_id, $sms_txt)
    {
//        dd("sendSMS telphone:" . $telphone . " template_id:" . $templated_id . " sms_txt:" . $sms_txt);
        // url中{function}/{operation}?部分
        $funAndOperate = "industrySMS/sendSMS";
        $body = self::createBasicAuthData();
//        $body['smsContent'] = "【短信测试】您的验证码是1018，30分钟内有效。by leek";
        // 模板id
        $body['templateid'] = $templated_id;
        // 模板中的变量
        $body['param'] = $sms_txt;
        $body['to'] = $telphone;
        $result = self::post($funAndOperate, $body);
        Log::info("sendSMS result:" . \GuzzleHttp\json_encode($result));
        return $result;
    }


    // 生成基础的请求参数
    public static function createBasicAuthData()
    {
        $timestamp = date("YmdHis");

        // 签名
        $sig = md5(self::$ACCOUNT_SID . self::$AUTH_TOKEN . $timestamp);
        return array("accountSid" => self::$ACCOUNT_SID, "timestamp" => $timestamp, "sig" => $sig, "respDataType" => "JSON");
    }

    public static function createUrl($funAndOperate)
    {
        // 时间戳
        date_default_timezone_set("Asia/Shanghai");
        $timestamp = date("YmdHis");

        return self::$BASE_URL . $funAndOperate;
    }

    // 创建请求头
    public static function createHeaders()
    {
        $headers = array('Content-type: ' . self::$CONTENT_TYPE, 'Accept: ' . self::$ACCEPT);

        return $headers;
    }

    //
    public static function post($funAndOperate, $body)
    {

        // 构造请求数据
        $url = self::createUrl($funAndOperate);
        $headers = self::createHeaders();

//        echo $url;
//        echo json_encode($body);
//        var_dump($headers);

        // 拼接字符串
        $fields_string = "";
        foreach ($body as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        // 提交请求
        $con = curl_init();
        curl_setopt($con, CURLOPT_URL, $url);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($con, CURLOPT_HEADER, 0);
        curl_setopt($con, CURLOPT_POST, 1);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($con, CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($con);
        curl_close($con);

        return "" . $result;
    }

}