<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Models\Client;
use App\Models\WxConfig;
use Auth;

class AccountController extends BaseController
{

    public function index()
    {
        $wxconfig = WxConfig::first();
        return view('admin.wechat.wx_account_index',['wxconfig' => $wxconfig]);
    }

    public function create()
    {
        $wxconfig = WxConfig::first();
        if (!$wxconfig) {
            $wxconfig = new WxConfig();
        }

        return view('admin.wechat.wx_account_edit', ['wxconfig' => $wxconfig]);
    }

    public function store(Request $request)
    {
        $wxconfig = WxConfig::findOrNew($request->input('id'));
        $wxconfig->wechat_id = $request->input('wechat_id');
        $wxconfig->source_id = $request->input('source_id');
        $wxconfig->name = $request->input('name');
        $wxconfig->appid = trim($request->input('appid'));
        $wxconfig->app_secret = trim($request->input('app_secret'));
        $wxconfig->token = trim($request->input('token'));
        $wxconfig->mch_id = trim($request->input('mch_id'));
        $wxconfig->sign_key = trim($request->input('sign_key'));
        $wxconfig->save();
        if ($wxconfig) {
            return $this->ajaxMessage('更新成功，跳转至列表');
        } else {
            return $this->ajaxError('更新失败，请重试');
        }
    }
}
