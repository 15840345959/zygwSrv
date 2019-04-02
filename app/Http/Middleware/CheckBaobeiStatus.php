<?php

namespace App\Http\Middleware;

use App\Components\BaobeiManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use Closure;
use Illuminate\Support\Facades\Log;


class CheckBaobeiStatus
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $request->all();
        //合规校验
        if (!array_key_exists('id', $data)) {
            return ApiResponse::makeResponse(false, ApiResponse::$returnMessage[ApiResponse::USER_ID_LOST], ApiResponse::USER_ID_LOST);
        }
        $baobei = BaobeiManager::getById($data['id']);
        if ($baobei->status == '0') {
            return ApiResponse::makeResponse(false, ApiResponse::$returnMessage[ApiResponse::BAOBEI_INVALID], ApiResponse::BAOBEI_INVALID);
        }
        return $next($request);
    }
}
