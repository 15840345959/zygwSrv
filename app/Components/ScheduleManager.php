<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use Qiniu\Auth;

class ScheduleManager
{
    /*
     * 报备超期计划任务
     *
     */
    public static function execBaobeiExceedSchedule()
    {
        $baobeis = BaobeiManager::getAllBaobeiExceedList();
        foreach ($baobeis as $baobei) {
            $baobei->status = '0';
            $baobei->save();
        }
    }

    /*
     * 成交超期计划任务
     */
    public static function execDealExceedSchedult()
    {
        $baobeis = BaobeiManager::getAllDealExceedList();
        foreach ($baobeis as $baobei) {
            $baobei->status = '0';
            $baobei->save();
        }
    }
}