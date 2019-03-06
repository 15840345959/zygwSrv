<?php

namespace App\Http\Middleware;

use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use Closure;
use Illuminate\Support\Facades\Log;


class CheckStatus
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
        if (!array_key_exists('user_id', $data)) {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::USER_ID_LOST], ApiResponse::USER_ID_LOST);
        }
        $user = UserManager::getById($data['user_id']);
        if ($user->status == '0') {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::USER_INVALID], ApiResponse::USER_INVALID);
        }
        return $next($request);
    }
}
