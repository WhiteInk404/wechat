<?php

namespace App\Http\Controllers;

class AppBaseController extends Controller
{
    public function sendResponse($data, $message = '')
    {
        return response()->json($this->makeResponse($message, $data));
    }

    public function sendError($data, $message = '')
    {
        return response()->json($this->makeError($message, $data));
    }

    protected function WXAConfig()
    {
        return config('wechat.mini_program');
    }

    /**
     * @param string $message
     * @param mixed  $data
     *
     * @return array
     */
    public function makeResponse($message, $data)
    {
        return [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public function makeError($message, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}
