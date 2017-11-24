<?php
namespace App\Services;

use App\Models\WxConfig;
use EasyWeChat\Foundation\Application;

class WechatSdkService
{
    protected $app;

    /**
     * 初始化微信配置
     * @param array $oauth
     * @return Application
     * @throws \Exception
     */
    public static function init($oauth = [])
    {
        $config = WxConfig::first();
        if (!$config) {
            throw  new \Exception('wxConfig not found');
        }
        $destinationPath = storage_path('certs/');
        $logPath = 'logs/easywechat-' . date('Y-m-d') . '.log';
        $options = [
            'debug' => true,
            'app_id' => $config->appid,
            'secret' => $config->app_secret,
            'token' => $config->token,
            'aes_key' => $config->aes_key,
            'log' => [
                'level' => 'debug',
                'file' => storage_path($logPath), // 绝对路径
            ],
            'payment' => [
                'merchant_id' => $config->mch_id,
                'key' => $config->sign_key,
                'notify_url' => ''
            ],
            'oauth' => $oauth
        ];
        $app = new Application($options);
        return $app;
    }
}