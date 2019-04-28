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

class Utils
{
    //分页配置
    const PAGE_SIZE = 15;


    /*
     * 判断一个对象是不是空
     *
     * By TerryQi
     *
     * 2017-12-23
     *
     */
    public static function isObjNull($obj)
    {
        if ($obj === null || $obj === "" || $obj === "undefined") {
            return true;
        }
        return false;
    }

    /*
     * 生成订单号-增加4个随机数位置
     *
     * By TerryQi
     *
     * 2017-01-12
     *
     */
    public static function generateTradeNo()
    {
        $trade_no = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return $trade_no . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
    }

    /*
     * 获取随机数-一般用于验证码
     *
     * By TerryQi
     *
     * 2018-08-19
     */
    public static function getRandNum($length)
    {
        $rand_str = "";
        for ($i = 0; $i < $length; $i++) {
            $rand_str = $rand_str . rand(0, 9);
        }
        return $rand_str;
    }


    /*
     * 获取范围内的随机数
     *
     * By TerryQi
     *
     * 2018-08-25
     */
    public static function getRandInRang($start, $end)
    {
        return rand($start, $end);
    }


    /**
     * @param $url 请求网址
     * @param bool $params 请求参数
     * @param int $ispost 请求方式
     * @param int $https https协议
     * @return bool|mixed
     */
    public static function curl($url, $params = false, $ispost = 0, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    /*
     * 去除emoj符号
     *
     * By TerryQi
     *
     * 2018-04-08
     */
    public static function removeEmoji($text)
    {
        $value = json_encode($text);
        $value = preg_replace("/\\\u[ed][0-9a-f]{3}\\\u[ed][0-9a-f]{3}/", "*", $value);//替换成*
        $value = json_decode($value);
        return $value;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///
    /// log相关
    ///
    ///
    /**
     * 请求接口LOG
     * @param string $logPath 请求接口
     * @param string $logIp IP地址
     * @param array $logData 请求参数
     */
    public static function requestLog($logPath = "", $logIp = "", $logData = [])
    {
        $LOGO_NO = 'LOG' . date('Ymdhis', time()) . rand(1000000, 10000000);
        Session::put('LOGO_NO', $LOGO_NO);
        //2019-01-24优化接口，避免存在过多的undefined错误
        if (array_key_exists('mch_code', $logData) && Utils::isObjNull($logData['mch_code'])) {
            $logData['mch_code'] = 'isart';
        }
        Log::info('[Request]  ' . $LOGO_NO . '  ' . $logPath . "(" . $logIp . ")   " . json_encode($logData));
    }

    /**
     * 过程中接口LOG
     * @param string $logModular 模块
     * @param string $logData 数据
     * @param string $logContent 备注
     */
    public static function processLog($logModular = "", $logContent = "", $logData = "")
    {
        $LOGO_NO = Session::get("LOGO_NO");
        if (is_array($logData)) {
            $logData = json_encode($logData, true);
        }
        if ($logContent) {
            Log::info('[Process]  ' . $LOGO_NO . '  ' . $logContent . '  ' . $logModular . '  ' . $logData);
        } else {
            Log::info('[Process]  ' . $LOGO_NO . '  ' . $logModular . '  ' . $logData);
        }
    }

    /**
     * 过程报错接口LOG
     * @param string $logData 数据
     */
    public static function errorLog($logData = "")
    {
        $LOGO_NO = Session::get("LOGO_NO");
        if (!$LOGO_NO) {
            $LOGO_NO = 'LOG' . date('Ymdhis', time()) . rand(1000000, 10000000);
            Session::put('LOGO_NO', $LOGO_NO);
        }
        if (is_array($logData)) {
            $logData = json_encode($logData, true);
        }
        Log::info('[Error]  ' . $LOGO_NO . '  ' . $logData);
        Session::remove("LOGO_NO");
    }

    /**
     * 返回接口LOG
     * @param string $logModular 模块
     * @param array $logData 数据
     */
    public static function backLog($logModular = "", $logData = [])
    {
        $LOGO_NO = Session::get("LOGO_NO");
        $log = array(
            'code' => $logData['code'],
            'result' => $logData['result'],
            'message' => $logData['message'],
        );
        if (array_key_exists('ret', $logData)) {
            $log['ret'] = $logData['ret'];
        }
        Log::info('[Back]  ' . $LOGO_NO . '  ' . $logModular . '  ' . json_encode($log, true));
        Session::remove("LOGO_NO");
    }

    /**
     * 自定义LOG
     * @param string $label log标签
     * @param string $logContent 备注
     * @param string/array $logData 数据
     */
    public static function customLog($label = "DEBUG", $logContent = "", $logData = "")
    {
        $LOGO_NO = Session::get("LOGO_NO");
        if (!$LOGO_NO) {
            $LOGO_NO = 'LOG' . date('Ymdhis', time()) . rand(1000000, 10000000);
            Session::put('LOGO_NO', $LOGO_NO);
        }
        if (is_array($logData)) {
            // 将数组转为字符串
            $logDataArray = $logData;
            $logData = '';
            foreach ($logDataArray as $key => $logDataRow) {
                if (is_array($logDataRow)) {
                    $logDataRow = json_encode($logDataRow, true);
                }
                $str = $key . "：" . $logDataRow;
                $logData .= $str . '  ';
            }
        }
        if ($logContent) {
            Log::info('[' . $label . ']  ' . $LOGO_NO . '  ' . $logContent . '  ' . $logData);
        } else {
            Log::info('[' . $label . ']  ' . $LOGO_NO . '  ' . $logData);
        }
        Session::remove("LOGO_NO");
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////


    /*
     * 下载图片$url:图片url，存入$path路径
     *
     * By TerryQi
     *
     * 2018-10-30
     */
    public static function downloadImage($url, $path)
    {
        $arr = parse_url($url);
        $img_name = pathinfo($arr['path'])['basename'];
//        dd($img_name);
        $file_content = file_get_contents($url);

        file_put_contents($path . $img_name, $file_content);

        return $img_name;
    }

    /*
     * 下载图片方法
     *
     * By TerryQi
     *
     * 2018-10-11
     *
     * url：下载链接  path：下载路径  filename：文件名
     *
     */
    public static function downloadFile($url, $path, $filename)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);

        $filename = $filename;
        $resource = fopen($path . '/' . $filename, 'a');
        fwrite($resource, $file);
        fclose($resource);

        return $filename;
    }

}