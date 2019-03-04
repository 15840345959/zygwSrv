<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin;

use App\Components\ClientManager;
use App\Components\TWManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\TW;
use App\Models\TWInfo;
use Illuminate\Http\Request;

class ClientController
{

    /*
     * 首页
     *
     * By mtt
     *
     * 2019-03-04
     */
    public static function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();

        $search_word = null;    //搜索条件
        $user_id = null;

        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('user_id', $data) && !Utils::isObjNull($data['user_id'])) {
            $user_id = $data['user_id'];
        }
        $con_arr = array(
            'search_word' => $search_word,
            'user_id' => $user_id
        );

//        dd($con_arr);

        //相关搜素条件
        $clients = ClientManager::getListByCon($con_arr, true);
        foreach ($clients as $client) {
            $client = ClientManager::getInfoByLevel($client, '');
        }
        return view('admin.client.index', ['admin' => $admin, 'datas' => $clients, 'con_arr' => $con_arr]);
    }
}





