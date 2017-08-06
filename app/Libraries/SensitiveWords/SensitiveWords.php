<?php

namespace App\Libraries\SensitiveWords;

use GuzzleHttp\Client;
use Log;

class SensitiveWords
{
    protected $server_url = 'http://www.hoapi.com/index.php/Home/Api/check';

    const ERROR_HAS_SENSITIVE_WORDS = 1; // 检测到敏感字符
    const ERROR_RATE_LIMIT          = 402; // 每秒超过一次请求

    public function filter($content)
    {
        if (!config('sensitive-words.enable')) {
            return $content;
        }

        $token = config('sensitive-words.token');

        try {
            $http = new Client();

            $response     = $http->post($this->server_url, ['form_params' => ['str' => $content, 'token' => $token]]);
            $response_arr = json_decode($response->getBody()->getContents(), true);
            if ($response_arr['status']) {
                return $content;
            } else {
                // 请求速率超过上限暂不做处理
                if ($response_arr['code'] == self::ERROR_HAS_SENSITIVE_WORDS) {
                    $content = $response_arr['data']['new_str'];
                }

                return $content;
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' exception', ['msg' => $e->getMessage(), 'content' => $content]);

            return $content;
        }
    }
}
