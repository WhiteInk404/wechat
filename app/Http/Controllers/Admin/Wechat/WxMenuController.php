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

class WxMenuController extends BaseController
{
    public function index()
    {
        $menu = WxMenu::where('type', WxMenu::TYPE_CODE)
            ->first();
        if (!$menu) {
            $menu = new WxMenu();
            $menu->name = '';
            $menu->type = WxMenu::TYPE_CODE;
            $menu->menu_id = '';
            $menu->button = '';
            $menu->matchrule = '';
            $menu->save();
        }
        return view('admin.wechat.wx_menu_index', ['wxMenu' => $menu]);
    }

    public function store(Request $request)
    {
        $menus = $request->input('menus', '');
        if (empty($menus)) {
            return redirect(route('admin.wechat.wxMenu.index'))->withInput()
                ->withErrors('请输入菜单代码');
        }
        $menu = WxMenu::where('type', WxMenu::TYPE_CODE)
            ->first();
        $menu->button = $menus;
        $menu->save();
        try {
            $app = WechatSdkService::init();
            $menuService = $app->menu;
            $menuService->add(json_decode($menus));
        } catch (\Exception $exception) {
            return redirect(route('admin.wechat.wxMenu.index'))->withInput()
                ->withErrors('发布菜单出错：' . $exception->getMessage());
        }
        return redirect(route('admin.wechat.wxMenu.index'))->withInput()
                ->withSuccess("更新成功");
    }
}
