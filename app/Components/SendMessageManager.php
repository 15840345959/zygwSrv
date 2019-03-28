<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use function GuzzleHttp\default_ca_bundle;
use Qiniu\Auth;

class SendMessageManager
{

    const CLIENT_COMMING = "CLIENT_COMMING";    //客户即将到访通知
    const USERUP_SUCCESS = "USERUP_SUCCESS";  //中介升级为案场负责人成功
    const PAY_ZHONGJIE = "PAY_ZHONGJIE";        //向中介支付
    const JIFEN_CHANGE = "JIFEN_CHANGE";        //积分变更
    const ORDER_DEAL = "ORDER_DEAL";        //订单成交
    const PAY_YONGJIN = "PAY_YONGJIN";        //支付佣金

    /*
     * 发送消息
     *
     * By TerryQi
     *
     */
    public static function sendMessage($user_id, $message_type, $message_content)
    {
        //dd($user_id);
        $user = UserManager::getByIdWithToken($user_id);
//        dd($user);
        //判断服务号是否为空，如果不为空则通过服务号发送消息
        if (!Utils::isObjNull($user->fwh_openid)) {
            self::setMessageFromFWH($user->fwh_openid, $message_type, $message_content);
        }
        //判断手机号是否为空，如果不为空则通过短信发送消息
        if (!Utils::isObjNull($user->phonenum)) {
            self::setMessageFromSM($user->phonenum, $message_type, $message_content);
        }
    }

    /*
     * 通过服务号发送消息
     *
     */
    private static function setMessageFromFWH($fwh_openid, $message_type, $message_content)
    {
        $template_id = self::getTemplateIdFromMessageType($message_type);
        //如果未设定模板，则返回
        if ($template_id == null) {
            return;
        }
        $message = self::getTemplateContentFromMessageType($message_type, $message_content);
        try {
            WechatManager::sendTemplateMessage($fwh_openid, $template_id, $message);
        } catch (\Exception $e) {
            Utils::errorLog($e);
        }

    }

    /*
     * 通过短信下发
     *
     */
    private static function setMessageFromSM($phonenum, $message_type, $message_content)
    {
        $template_id = self::getSMSTemplateIdFromMessageType($message_type);
        //如果未设定模板，则返回
        if ($template_id == null) {
            return;
        }
        $message = self::getSMSTemplateContentFromMessageType($message_type, $message_content);
        SMSManager::sendSMS($phonenum, $template_id, $message);
    }

    /*
     * 通过messageType和messageContent获取服务号模板类型
     *
     */
    private static function getTemplateContentFromMessageType($message_type, $message_content)
    {
        //根据消息类型选择模板
        switch ($message_type) {
            case self::CLIENT_COMMING:  //客户到访通知
                return [
                    'first' => '预约到访通知',
                    'keyword1' => $message_content["keyword3"],
                    'keyword2' => $message_content["keyword4"],
                    'keyword3' => $message_content["keyword1"],
                    'keyword4' => $message_content["keyword2"],
                    'remark' => '报备列表中查看'
                ];
            case self::USERUP_SUCCESS:  //升级案场负责人成功
                return [
                    'first' => '升级案场负责人',
                    'keyword1' => $message_content["keyword1"],
                    'keyword2' => '审核通过',
                    'remark' => '下拉刷新个人中心，使用案场负责人相关操作'
                ];
            case self::JIFEN_CHANGE:  //积分变更通知
                return [
                    'first' => '积分变更通知',
                    'keyword1' => $message_content["keyword1"],
                    'keyword2' => $message_content["keyword2"],
                    'keyword3' => $message_content["keyword3"],
                    'remark' => '在个人中心的积分商城中兑换商品'
                ];
            case self::ORDER_DEAL:  //订单成交
                return [
                    'first' => '报备客户成交',
                    'keyword1' => $message_content["keyword1"],
                    'keyword2' => $message_content["keyword2"],
                    'keyword3' => $message_content["keyword3"],
                    'keyword4' => $message_content["keyword4"],
                    'remark' => '请及时与案场负责人核对报备单'
                ];
            case self::PAY_ZHONGJIE:  //佣金结算
                return [
                    'first' => '佣金结算',
                    'customName' => $message_content["keyword1"],
                    'customPhone' => $message_content["keyword2"],
                    'reportBuilding' => $message_content["keyword5"],
                    'reportTime' => $message_content["keyword6"],
                    'signAmount' => '--',
                    'signTime' => '--',
                    'commissionAmount' => $message_content["keyword3"],
                    'commissionTime' => $message_content["keyword4"],
                    'remark' => '请及时查收佣金结算情况'
                ];
            default:
                break;
        }
    }

    /*
     * 根据messageType获取模板id
     *
     */
    private static function getTemplateIdFromMessageType($message_type)
    {
        //根据消息类型选择模板
        switch ($message_type) {
            case self::CLIENT_COMMING:  //客户即将到访
                return "xr099vOdxB8bR2isIoCZqSQj5aweHDx-tHAGrnNekHs";
            case self::USERUP_SUCCESS:  //升级案场负责人成功
                return "4CXYxeuUCJJ5VhRNeoWtk-zCf-F1IMLp5UqXt7lgPfU";
            case self::JIFEN_CHANGE:  //积分变更
                return "4pvby9Ld9joWccEn_-71RQWasbw_Z-ME8R2yxa3OJXE";
            case self::ORDER_DEAL:  //报备单成交
                return "Ixg4z4X7vgKBcEzHzMu30dEWE8_Ed5zssJtL7cC_3qA";
            case self::PAY_ZHONGJIE:  //报备单成交
                return "ldN3V5ExXJYdtqrvdewdFXZDYZN362fW86cb2dB_yeA";
            default:
                break;
        }
        return null;
    }


    /*
     * 通过messageType和messageContent获取短信模板类型
     *
     */
    private static function getSMSTemplateContentFromMessageType($message_type, $message_content)
    {
        //根据消息类型选择模板
        switch ($message_type) {
            case self::CLIENT_COMMING:  //客户即将到访
                return $message_content['keyword1'] . ',' . $message_content['keyword2'] . ',' . $message_content['keyword3'] . ',' . $message_content['keyword4'];
            case self::USERUP_SUCCESS:  //中介升级为置业顾问
                return $message_content['keyword1'] . ',' . $message_content['keyword2'];
            case self::PAY_ZHONGJIE:
                return $message_content['keyword1'] . ',' . $message_content['keyword2'] . ',' . $message_content['keyword3'] . ',' . $message_content['keyword4'];
            default:
                break;
        }
    }

    /*
     * 根据messageType获取短信模板id
     *
     */
    private static function getSMSTemplateIdFromMessageType($message_type)
    {
        //根据消息类型选择模板
        switch ($message_type) {
            case self::CLIENT_COMMING:      //客户即将到访
                return "170823977";
            case self::USERUP_SUCCESS:       //中介升级为案场负责人成功
                return "163798279";
            case self::PAY_ZHONGJIE:       //中介升级为案场负责人成功
                return "179832253";
            default:
                break;
        }
        return null;
    }

}