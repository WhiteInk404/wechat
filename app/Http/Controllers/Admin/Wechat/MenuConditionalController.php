<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\WxMenu;
use App\Models\WxConfig;
use App\Http\Controllers\BaseController;
use App\Services\WechatSdkService;
use Session;
use View;
use Auth;

class MenuConditionalController extends BaseController
{
    public function index()
    {

        $menus = WxMenu::where('type', WxMenu::TYPE_CONDITIONAL)
            ->paginate(15);
        return view('admin.wechat.menu_conditional_index', ['menus' => $menus]);
    }

    public function create()
    {
        $data = [
            'type' => WxMenu::TYPE_CONDITIONAL,
            'wxMenu' => 0
        ];
        return view('admin.wechat.menu_edit', $data);
    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function destroy(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }

    public function sync(Request $request)
    {

    }

}
