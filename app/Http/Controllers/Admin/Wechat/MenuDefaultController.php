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

class MenuDefaultController extends BaseController
{

    public function index()
    {
        $menu = WxMenu::where('type', WxMenu::TYPE_DEFAULT)
            ->first();
        return view('admin.wechat.menu_edit', [
            'wxMenu' => $menu,
            'type' => WxMenu::TYPE_DEFAULT,
            ]);
    }

    public function store(Request $request)
    {
        $button = $request->input('button');
        if (!is_array($button) || count($button) == 0) {
            return $this->ajaxError('参数不正确');
        }
        $menu = WxMenu::where('type', WxMenu::TYPE_DEFAULT)->first();
        if (!$menu) {
            $menu = new WxMenu();
            $menu->name = '';
            $menu->type = WxMenu::TYPE_DEFAULT;
            $menu->menu_id = '';
            $menu->matchrule = [];
        }
        try {
            $app = WechatSdkService::init();
            $menuService = $app->menu;
            $menuService->add($button);
        } catch (\Exception $exception) {
            return $this->ajaxError('发布失败');
        }

        $menu->button = $button;
        $menu->save();
        return $this->ajaxMessage(['id' => $menu->id, 'menuId' => '']);
    }

    public function delete(Request $request)
    {
        return $this->ajaxError('清空数据失败');
        $id = intval($request->input('id', 0));
        $menuModel = WxMenu::where('id', $id)
            ->where('type', WxMenu::TYPE_DEFAULT)
            ->first();
        if (!$menuModel) {
            return $this->ajaxError('清空数据失败');
        }
        $app = WechatSdkService::init();
        $menu = $app->menu;
        $res = $menu->destroy();
        if (!$res || $res['errcode']) {
            return $this->ajaxError('清空数据失败');
        }
        $menuModel->button = [];
        $res = $menuModel->save();
        if (!$res) {
            return $this->ajaxError('清空数据失败');
        }
        return $this->ajaxMessage('清空数据成功');
    }

    public function sync(Request $request)
    {
        return $this->ajaxError('同步菜单失败');
        $menuModel = WxMenu::where('type', WxMenu::TYPE_DEFAULT)
            ->first();
        if (!$menuModel) {
            $menuModel = new WxMenu();
            $menuModel->name = '';
            $menuModel->type = WxMenu::TYPE_DEFAULT;
            $menuModel->menu_id = '';
            $menuModel->matchrule = [];
        }
        $app = WechatSdkService::init();
        $menu = $app->menu;
        $wxMenus = $menu->current();
        if ($wxMenus->has('selfmenu_info') && array_key_exists('button', $wxMenus->selfmenu_info)) {
            $button = $wxMenus->selfmenu_info['button'];
        } else {
            return $this->ajaxError('同步菜单失败');
        }
        $menuModel->button = $button;
        $res = $menuModel->save();
        if (!$res) {
            return $this->ajaxError('同步菜单失败');
        }
        $data = [
            'id' => $menuModel->id,
            'menuId' => '',
            'button' => $button
        ];
        return $this->ajaxMessage($data);
    }
}
