<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Baobei;
use App\Models\BaobeiBuyPurpose;
use App\Models\BaobeiClientCare;
use App\Models\BaobeiKnowWay;
use App\Models\BaobeiPayWay;
use App\Models\HouseArea;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class BaobeiManager
{
    /*
     * 获取全部报备信息
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getBaobeiOptions()
    {
        $pay_ways = BaobeiPayWayManager::getListByCon(['status' => '1'], false);
        $buy_purposes = BaobeiBuyPurposeManager::getListByCon(['status' => '1'], false);
        $know_ways = BaobeiKnowWayManager::getListByCon(['status' => '1'], false);
        $client_cares = BaobeiClientCareManager::getListByCon(['status' => '1'], false);
        $areas = HouseAreaManager::getListByCon(['status' => '1'], false);

        $infoOption = new Collection([
            'pay_ways' => $pay_ways,
            'buy_purposes' => $buy_purposes,
            'know_ways' => $know_ways,
            'client_cares' => $client_cares,
            'areas' => $areas,
        ]);
        return $infoOption;
    }


    /*
     * 根据id获取报备详情
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getById($id)
    {
        $info = Baobei::find($id);
        return $info;
    }

    /*
     * 获取报备详细信息
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     * 0 带客户信息 1：带中介信息 2：带楼盘信息
     */
    public static function getInfoByLevel($info, $level)
    {
        Utils::processLog(__METHOD__, '', " " . "info:" . json_encode($info));

        $info->status_str = Value::COMMON_STATUS_VAL[$info->status];

        $info->baobei_status_str = Value::BAOBEI_STATUS_VAL[$info->baobei_status];
        $info->pay_zhongjie_status_str = Value::BAOBEI_PAY_ZHONGJIE_STATUS_VAL[$info->pay_zhongjie_status];
        $info->can_jiesuan_status_str = Value::BAOBEI_CAN_JIESUAN_STATUS_VAL[$info->can_jiesuan_status];
        $info->intention_status_str = Value::BAOBEI_INTENTION_STATUS_VAL[$info->intention_status];
        $info->visit_way_str = Value::BAOBEI_VISIT_WAY_VAL[$info->visit_way];

        //0：带客户信息
        if (strpos($level, '0') !== false) {
            $info->client = ClientManager::getById($info->client_id);
        }
        //1：带中介信息
        if (strpos($level, '1') !== false) {
            $user = UserManager::getById($info->user_id);
            $user = UserManager::getInfoByLevel($user, '');
            $info->user = $user;
        }
        //2：带楼盘信息
        if (strpos($level, '2') !== false) {
            $house = HouseManager::getById($info->house_id);
            $house = HouseManager::getInfoByLevel($house, '');
            unset($house->content_html);
            $info->house = $house;
        }

        if (!Utils::isObjNull($info->anchang_id)) {
            $anchang = UserManager::getById($info->anchang_id);
            $anchang = UserManager::getInfoByLevel($anchang, '');
            $info->anchang = $anchang;
        }
        //置业顾问
        if (!Utils::isObjNull($info->guwen_id)) {
            $info->guwen = ZYGWManager::getById($info->guwen_id);
        }
        //区域
        if (!Utils::isObjNull($info->area_id)) {
            $info->area = HouseAreaManager::getById($info->area_id);
        }
        //认知途径
        if (!Utils::isObjNull($info->way_id)) {
            $info->know_way = BaobeiKnowWayManager::getById($info->way_id);
        }
        //购房目的
        if (!Utils::isObjNull($info->purpose_id)) {
            $info->purpose = BaobeiBuyPurposeManager::getById($info->purpose_id);
        }
        //关注点
        if (!Utils::isObjNull($info->care_id)) {
            $info->care = BaobeiClientCareManager::getById($info->care_id);
        }
        //产品信息
        if (!Utils::isObjNull($info->deal_huxing_id)) {
            $deal_huxing = HuxingManager::getById($info->deal_huxing_id);
            $deal_huxing = HuxingManager::getInfoByLevel($deal_huxing, '');
            $info->deal_huxing = $deal_huxing;
        }
        //支付方式
        if (!Utils::isObjNull($info->pay_way_id)) {
            $info->pay_way = BaobeiPayWayManager::getById($info->pay_way_id);
        }
        //支付中介的管理员
        if (!Utils::isObjNull($info->pay_admin_id)) {
            $info->admin = AdminManager::getById($info->pay_admin_id);
        }
        return $info;
    }

    /*
     * 判断客户是否已经报备过
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function isClientAlreadyBaobeiByHouseId($client_id, $house_id)
    {
        $info = self::getListByCon(['status' => '1', 'client_id' => $client_id, 'house_id' => $house_id], false)->first();
        return $info;
    }

    /*获取全部的楼盘标签信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new Baobei();

        if (array_key_exists('trade_no', $con_arr) && !Utils::isObjNull($con_arr['trade_no'])) {
            $infos = $infos->where('trade_no', 'like', '%' . $con_arr['trade_no'] . '%');
        }
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('status_arr', $con_arr) && !Utils::isObjNull($con_arr['status_arr'])) {
            $infos = $infos->wherein('status', $con_arr['status_arr']);
        }
        if (array_key_exists('baobei_status_arr', $con_arr) && !Utils::isObjNull($con_arr['baobei_status_arr'])) {
            $infos = $infos->wherein('baobei_status', $con_arr['baobei_status_arr']);
        }
        if (array_key_exists('can_jiesuan_status_arr', $con_arr) && !Utils::isObjNull($con_arr['can_jiesuan_status_arr'])) {
            $infos = $infos->wherein('can_jiesuan_status', $con_arr['can_jiesuan_status_arr']);
        }
        if (array_key_exists('pay_zhongjie_status_arr', $con_arr) && !Utils::isObjNull($con_arr['pay_zhongjie_status_arr'])) {
            $infos = $infos->wherein('pay_zhongjie_status', $con_arr['pay_zhongjie_status_arr']);
        }
        if (array_key_exists('client_id', $con_arr) && !Utils::isObjNull($con_arr['client_id'])) {
            $infos = $infos->where('client_id', '=', $con_arr['client_id']);
        }
        if (array_key_exists('house_id', $con_arr) && !Utils::isObjNull($con_arr['house_id'])) {
            $infos = $infos->where('house_id', '=', $con_arr['house_id']);
        }
        if (array_key_exists('baobei_status', $con_arr) && !Utils::isObjNull($con_arr['baobei_status'])) {
            $infos = $infos->where('baobei_status', '=', $con_arr['baobei_status']);
        }
        if (array_key_exists('can_jiesuan_status', $con_arr) && !Utils::isObjNull($con_arr['can_jiesuan_status'])) {
            $infos = $infos->where('can_jiesuan_status', '=', $con_arr['can_jiesuan_status']);
        }
        if (array_key_exists('pay_zhongjie_status', $con_arr) && !Utils::isObjNull($con_arr['pay_zhongjie_status'])) {
            $infos = $infos->where('pay_zhongjie_status', '=', $con_arr['pay_zhongjie_status']);
        }
        if (array_key_exists('start_time', $con_arr) && !Utils::isObjNull($con_arr['start_time'])) {
            $infos = $infos->where('created_at', '>', $con_arr['start_time']);
        }
        if (array_key_exists('end_time', $con_arr) && !Utils::isObjNull($con_arr['end_time'])) {
            $infos = $infos->where('created_at', '<=', $con_arr['end_time']);
        }
        if (array_key_exists('ids_arr', $con_arr) && !Utils::isObjNull($con_arr['ids_arr'])) {
            $infos = $infos->wherein('id', $con_arr['ids_arr']);
        }

        $infos = $infos->orderby('id', 'desc');
        if ($is_paginate) {
            $infos = $infos->paginate(Utils::PAGE_SIZE);
        } else {
            $infos = $infos->get();
        }
        return $infos;
    }


    /*
     * 设置报备信息，用于编辑
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('trade_no', $data)) {
            $info->trade_no = array_get($data, 'trade_no');
        }
        if (array_key_exists('client_id', $data)) {
            $info->client_id = array_get($data, 'client_id');
        }
//        if (array_key_exists('user_id', $data)) {         //不能在此处设置user_id，即中介的id，否则多处接口将收到影响
//            $info->user_id = array_get($data, 'user_id');
//        }
        if (array_key_exists('house_id', $data)) {
            $info->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('anchang_id', $data)) {
            $info->anchang_id = array_get($data, 'anchang_id');
        }
        if (array_key_exists('guwen_id', $data)) {
            $info->guwen_id = array_get($data, 'guwen_id');
        }
        if (array_key_exists('area_id', $data)) {
            $info->area_id = array_get($data, 'area_id');
        }
        if (array_key_exists('address', $data)) {
            $info->address = array_get($data, 'address');
        }
        if (array_key_exists('size', $data)) {
            $info->size = array_get($data, 'size');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        if (array_key_exists('Info_status', $data)) {
            $info->Info_status = array_get($data, 'Info_status');
        }
        if (array_key_exists('way_id', $data)) {
            $info->way_id = array_get($data, 'way_id');
        }
        if (array_key_exists('purpose_id', $data)) {
            $info->purpose_id = array_get($data, 'purpose_id');
        }
        if (array_key_exists('care_id', $data)) {
            $info->care_id = array_get($data, 'care_id');
        }
        if (array_key_exists('yongjin', $data)) {
            $info->yongjin = array_get($data, 'yongjin');
        }
        if (array_key_exists('intention_status', $data)) {
            $info->intention_status = array_get($data, 'intention_status');
        }
        if (array_key_exists('remark', $data)) {
            $info->remark = array_get($data, 'remark');
        }
        if (array_key_exists('plan_visit_time', $data)) {
            $info->plan_visit_time = array_get($data, 'plan_visit_time');
        }
        if (array_key_exists('visit_way', $data)) {
            $info->visit_way = array_get($data, 'visit_way');
        }
        if (array_key_exists('visit_time', $data)) {
            $info->visit_time = array_get($data, 'visit_time');
        }
        if (array_key_exists('visit_attach', $data)) {
            $info->visit_attach = array_get($data, 'visit_attach');
        }
        if (array_key_exists('deal_time', $data)) {
            $info->deal_time = array_get($data, 'deal_time');
        }
        if (array_key_exists('deal_size', $data)) {
            $info->deal_size = array_get($data, 'deal_size');
        }
        if (array_key_exists('deal_price', $data)) {
            $info->deal_price = array_get($data, 'deal_price');
        }
        if (array_key_exists('deal_huxing_id', $data)) {
            $info->deal_huxing_id = array_get($data, 'deal_huxing_id');
        }
        if (array_key_exists('deal_room', $data)) {
            $info->deal_room = array_get($data, 'deal_room');
        }
        if (array_key_exists('pay_way_id', $data)) {
            $info->pay_way_id = array_get($data, 'pay_way_id');
        }
        if (array_key_exists('sign_time', $data)) {
            $info->sign_time = array_get($data, 'sign_time');
        }
        if (array_key_exists('qkdz_time', $data)) {
            $info->qkdz_time = array_get($data, 'qkdz_time');
        }
        if (array_key_exists('can_jiesuan_status', $data)) {
            $info->can_jiesuan_status = array_get($data, 'can_jiesuan_status');
        }
        if (array_key_exists('can_jiesuan_time', $data)) {
            $info->can_jiesuan_time = array_get($data, 'can_jiesuan_time');
        }
        if (array_key_exists('pay_zhongjie_status', $data)) {
            $info->pay_zhongjie_status = array_get($data, 'pay_zhongjie_status');
        }
        if (array_key_exists('pay_zhongjie_time', $data)) {
            $info->pay_zhongjie_time = array_get($data, 'pay_zhongjie_time');
        }
        if (array_key_exists('pay_admin_id', $data)) {
            $info->pay_admin_id = array_get($data, 'pay_admin_id');
        }
        if (array_key_exists('pay_zhongjie_attach', $data)) {
            $info->pay_zhongjie_attach = array_get($data, 'pay_zhongjie_attach');
        }
        return $info;
    }


    /*
       * 根据状态获取报备统计
       *
       * By TerryQi
       *
       * 2018-02-28
       *
       */
    public static function getBaobeiStmtByStatus($info_status_arr, $can_jiesuan_status_arr, $pay_zhongjie_status_arr, $house_id, $start_time, $end_time)
    {
        $infos = Baobei::wherein('status', ['0', '1']);
        if ($info_status_arr != null) {
            $infos = $infos->wherein('baobei_status', $info_status_arr);
        }
        if ($can_jiesuan_status_arr != null) {
            $infos = $infos->wherein('can_jiesuan_status', $can_jiesuan_status_arr);
        }
        if ($pay_zhongjie_status_arr != null) {
            $infos = $infos->wherein('pay_zhongjie_status', $pay_zhongjie_status_arr);
        }
        if ($house_id != null) {
            $infos = $infos->where('house_id', '=', $house_id);
        }
        if ($start_time != null) {
            $infos = $infos->where('created_at', '>=', $start_time);
        }
        if ($end_time != null) {
            $infos = $infos->where('created_at', '<', $end_time);
        }
        $count = $infos->orderby('id', 'desc')->count();
        return $count;
    }

    /*
     * 报备趋势信息
     *
     * By TerryQi
     *
     * 2018-02-28
     */
    public static function getDaofangTrend($house_id, $start_time, $end_time)
    {
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , COUNT(*) - 1 as nums FROM zygwdb.t_date_list date_list left join zygwdb.t_baobei_info baobei_info on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(baobei_info.visit_time,'%Y-%m-%d') ";
        $first_con_flag = true;
        if ($house_id != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " house_id = " . $house_id . " ";
        }
        if ($start_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date >= '" . $start_time . "' ";
        }
        if ($end_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date <= '" . $end_time . "' ";
        }
        $sql_str = $sql_str . " GROUP BY tjdate";

//        dd($sql_str); //测试sql用
        $data = DB::select($sql_str);
        return $data;
    }


    /*
    * 成交生成佣金趋势信息
     *
     * By TerryQi
     *
     * 2018-02-28
     */
    public static function getShengchengYongjinTrend($house_id, $start_time, $end_time)
    {
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , SUM(baobei_info.yongjin) as yongjin FROM zygwdb.t_date_list date_list left join zygwdb.t_baobei_info baobei_info on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(baobei_info.deal_time,'%Y-%m-%d') ";
        $first_con_flag = true;
        if ($house_id != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " house_id = " . $house_id . " ";
        }
        if ($start_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date >= '" . $start_time . "' ";
        }
        if ($end_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date <= '" . $end_time . "' ";
        }
        $sql_str = $sql_str . " GROUP BY tjdate";

//        dd($sql_str); //测试sql用
        $data = DB::select($sql_str);
        return $data;
    }


    /*
    * 确认佣金趋势信息
     *
     * By TerryQi
     *
     * 2018-02-28
     */
    public static function getQueRenYongjinTrend($house_id, $start_time, $end_time)
    {
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , SUM(baobei_info.yongjin) as yongjin FROM zygwdb.t_date_list date_list left join zygwdb.t_baobei_info baobei_info on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(baobei_info.can_jiesuan_time,'%Y-%m-%d') ";
        $first_con_flag = true;
        if ($house_id != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " house_id = " . $house_id . " ";
        }
        if ($start_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date >= '" . $start_time . "' ";
        }
        if ($end_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date <= '" . $end_time . "' ";
        }
        $sql_str = $sql_str . " GROUP BY tjdate";

//        dd($sql_str); //测试sql用
        $data = DB::select($sql_str);
        return $data;
    }


    /*
   * 支付佣金趋势信息
    *
    * By TerryQi
    *
    * 2018-02-28
    */
    public static function getZhiFuYongjinTrend($house_id, $start_time, $end_time)
    {
        $sql_str = "SELECT DATE_FORMAT(date_list.date, '%Y-%m-%d') as tjdate , SUM(baobei_info.yongjin) as yongjin FROM zygwdb.t_date_list date_list left join zygwdb.t_baobei_info baobei_info on DATE_FORMAT(date_list.date,'%Y-%m-%d') = DATE_FORMAT(baobei_info.pay_zhongjie_time,'%Y-%m-%d') ";
        $first_con_flag = true;
        if ($house_id != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " house_id = " . $house_id . " ";
        }
        if ($start_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date >= '" . $start_time . "' ";
        }
        if ($end_time != null) {
            if ($first_con_flag) {
                $sql_str = $sql_str . " where ";
                $first_con_flag = false;
            } else {
                $sql_str = $sql_str . " and ";
            }
            $sql_str = $sql_str . " date_list.date <= '" . $end_time . "' ";
        }
        $sql_str = $sql_str . " GROUP BY tjdate";

//        dd($sql_str); //测试sql用
        $data = DB::select($sql_str);
        return $data;
    }


    /*
    * 根据状态获取佣金金额
    *
    * By TerryQi
    *
    * 2018-02-28
    *
    */
    public static function getYongjinStmtByStatus($info_status_arr, $can_jiesuan_status_arr, $pay_zhongjie_status_arr, $house_id, $start_time, $end_time)
    {
        $infos = Baobei::wherein('status', ['0', '1']);
        if ($info_status_arr != null) {
            $infos = $infos->wherein('baobei_status', $info_status_arr);
        }
        if ($can_jiesuan_status_arr != null) {
            $infos = $infos->wherein('can_jiesuan_status', $can_jiesuan_status_arr);
        }
        if ($pay_zhongjie_status_arr != null) {
            $infos = $infos->wherein('pay_zhongjie_status', $pay_zhongjie_status_arr);
        }
        if ($house_id != null) {
            $infos = $infos->where('house_id', '=', $house_id);
        }
        if ($start_time != null) {
            $infos = $infos->where('created_at', '>', $start_time);
        }
        if ($end_time != null) {
            $infos = $infos->where('created_at', '<=', $end_time);
        }
        $yongjin = $infos->orderby('id', 'desc')->sum('yongjin');
        return $yongjin;
    }

    //计划任务，获取全部的超期报备数据
    /*
     * 超期逻辑为status==1且baobei_status==0且created_at时间超过当前时间
     *
     */
    public static function getAllBaobeiExceedList()
    {
        $curr = DateTool::getCurrentTime();
        $infos = Baobei::where('status', '=', '1')->where('baobei_status', '=', '0')->where('created_at', '<', $curr)->get();
        return $infos;
    }

    //计划任务，获取全部的超期成交数据
    /*
     * 超期逻辑为status==1且baobei_status==1且visit_time大于30天
     *
     *
     */
    public static function getAllDealExceedList()
    {
        $curr = DateTool::getCurrentTime();
        $exceed_date = DateTool::dateAdd('D', -30, $curr, null);
//        dd($exceed_date);
        $infos = Baobei::where('status', '=', '1')->where('baobei_status', '=', '1')->where('visit_time', '<', $exceed_date)->get();
        return $infos;
    }


    /*
     * 中介按照到访量进行排名
     *
     * By TerryQi
     *
     * 2018-03-19
     */
    public static function zhongjiePaiming()
    {
        $sql_str = "SELECT count(*) as num,user_id FROM zygwdb.t_baobei_info where baobei_status in ('1','2','3','4') group by user_id order by num desc;";
        $data = DB::select($sql_str);
        return $data;
    }

    /*
     * 根据用户id获取全部佣金信息
     *
     * By TerryQi
     *
     * 2018-02-22
     *
     */
    public static function getAllYongjinByUserId($user_id)
    {
        $all_yongjin = Baobei::where('user_id', '=', $user_id)->where('status', '=', '1')->sum('yongjin');
        return $all_yongjin;
    }

    /*
     * 根据用户id获取待结算佣金
     *
     * By TerryQi
     *
     * 2018-02-22
     */
    public static function getWaitingForPayByUserId($user_id)
    {
        $waingtingForPay_yongjin = Baobei::where('user_id', '=', $user_id)->where('status', '=', '1')
            ->where('can_jiesuan_status', '=', '1')->where('pay_zhongjie_status', '=', '0')->sum('yongjin');
        return $waingtingForPay_yongjin;
    }
}