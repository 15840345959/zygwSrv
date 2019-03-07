<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/12/4
 * Time: 9:23
 */

namespace App\Components;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Value
{
    //小程序
    const ACCOUNT_CONFIG = "wechat.mini_program";     //配置文件位置

    //分页配置
    const PAGE_SIZE = 15;

    //通用的status
    const COMMON_STATUS_VAL = ['0' => '失效', '1' => '生效'];

    //管理员角色
    const ADMIN_ROLE_VAL = ['0' => '管理员', '1' => '超级管理员'];

    //广告位管理
    const AD_TYPE_VAL = ['0' => '不跳转', '1' => '跳转内容'];


    //人员角色
    const USER_TYPE_VAL = ['0' => '中介人员', '1' => '案场负责人'];


    //用户申请案场信息
    const USER_UP_STATUS_VAL = ['0' => '待审核', '1' => '审核通过', '2' => '审核驳回'];

    //户型信息
    const HUXING_YONGJIN_TYPE_VAL = ['0' => '按固定金额', '1' => '按成交比例'];

    //白皮书类型
    const TW_TYPE_VAL = ['1' => '合作细则', '2' => '行业白皮书', '3' => '积分兑换规则'];


    //兑换状态
    const GOODS_EXCHANGE_STATUS_VAL = ['0' => '未兑付', '1' => '已兑付'];

    //报备相关
    const BAOBEI_STATUS_VAL = ['0' => '报备', '1' => '到访', '2' => '成交', '3' => '签约', '4' => '全款到账'];
    const BAOBEI_CAN_JIESUAN_STATUS_VAL = ['0' => '不可结算', '1' => '可以结算'];
    const BAOBEI_PAY_ZHONGJIE_STATUS_VAL = ['0' => '未支付', '1' => '已支付'];
    const BAOBEI_VISIT_WAY_STATUS_VAL = ['0' => '中介带访', '1' => '自行到访'];

}