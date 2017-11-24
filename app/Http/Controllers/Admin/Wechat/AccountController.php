<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\WxConfig;

class AccountController extends BaseController
{
    public function index()
    {
        $wxConfig = WxConfig::first();
        return view('admin.wechat.index',['wxConfig' => $wxConfig]);
    }

    public function create()
    {
        $wxConfig = WxConfig::first();
        if (!$wxConfig) {
            $wxConfig = new WxConfig();
        }
        return view('admin.wechat.edit', ['wxConfig' => $wxConfig]);
    }

    public function store(Request $request)
    {
        $wxConfig = WxConfig::findOrNew($request->input('id'));
        $wxConfig->wechat_id = $request->input('wechat_id', '');
        $wxConfig->source_id = $request->input('source_id', '');
        $wxConfig->name = $request->input('name', '');
        $wxConfig->appid = trim($request->input('appid', ''));
        $wxConfig->app_secret = trim($request->input('app_secret', ''));
        $wxConfig->token = trim($request->input('token', ''));
        $wxConfig->mch_id = trim($request->input('mch_id', ''));
        $wxConfig->sign_key = trim($request->input('sign_key', ''));
        $wxConfig->aes_key = trim($request->input('aes_key', ''));
        $wxConfig->extra = '';
        $wxConfig->save();
        if ($wxConfig) {
            return redirect(route('admin.wechat.wxConfig.index'))
                ->withSuccess("更新成功");
        } else {
            return redirect(route('wxConfig.edit'))
                ->withErrors('更新失败，请重试');
        }
    }
}
