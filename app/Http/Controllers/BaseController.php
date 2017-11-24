<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * 每页显示个数
     *
     * @param int $max
     * @return int
     */
    public function perpage($max = 50)
    {
        $perpage = intval(app('request')->input('per_page', 20));

        return $perpage > $max ? $max : $perpage;
    }

    /**
     * @param string $errorMessage 错误提示
     * @param int $errorCode 错误代码
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxError($errorMessage = '', $errorCode = 1)
    {
        $result = [
            'error_code'    => $errorCode,
            'error_message' => $errorMessage,
            'data'          => ''
        ];

        return response()->json($result);
    }

    /**
     * ajax 返回
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxMessage($data)
    {
        $result = [
            'error_code'    => 0,
            'error_message' => '',
            'data'          => $data
        ];

        return response()->json($result);
    }

    /**
     * 用于后台管理错误提示使用
     * @param $message
     * @param string $url
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showErrorAdmin($message, $url = '')
    {
        return view('company.error', ['msg' => $message, 'url' => $url]);
    }
}
