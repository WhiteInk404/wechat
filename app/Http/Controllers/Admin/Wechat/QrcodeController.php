<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\WxQrcode;
use App\Models\WxConfig;
use App\Http\Controllers\BaseController;
use App\Services\WechatSdkService;
use Session;
use View;
use Auth;

class QrcodeController extends BaseController
{

    public function index()
    {
        $qrcodes = WxQrcode::orderBy('id', 'desc')->paginate(20);
        $result = count($qrcodes);
        return view('admin.wechat.qrcode_index', ['qrcodes' => $qrcodes, 'result'=> $result]);
    }

    public function create()
    {
        $data['action'] = 'add';
        $data['id'] = 0;
        $data['fields'] = $this->getFields();
        return view('admin.wechat.qrcode_edit', $data);
    }

    public function edit($id)
    {
        if (!$id) {
            return $this->showErrorAdmin('ID不存在', '/company/wx/qrcode');
        }
        $client = Auth::guard('company')->user();
        $qrcode = WxQrcode::where('id', '=', $id)->where('cid', $client->id)->first();
        if (!$qrcode) {
            return $this->showErrorAdmin('未找到该条信息', '/company/wx/qrcode');
        }
        $data['action'] = 'edit';
        $data['id'] = $id;
        $data['fields'] = $this->getFields($qrcode);
        $data['qrcode'] = $qrcode;
        return view('admin.wechat.qrcode_edit', $data);
    }

    public function store(Request $request)
    {
        $client = Auth::guard('company')->user();
        $scene_id = intval(trim($request->input('scene_id')));
        if (!$scene_id) {
            return $this->ajaxError('场景ID必须大于0');
        }
        $type = intval($request->input('type'));
        $remark = $request->input('remark');
        $action = $request->input('action');
        $qrcode = WxQrcode::where('scene_id', '=', $scene_id)
            ->where('cid', '=', $client->id)
            ->first();
        if ($action == 'add') {
            if ($qrcode) {
                return $this->ajaxError('场景ID已经存在');
            }
            $app = WechatSdkService::initByCid($client->id);
            $qrcode = $app->qrcode;
            $result = $qrcode->forever($scene_id);
            if (!$result) {
                return $this->ajaxError('生成二维码失败');
            }
            $qrcodeModel = new WxQrcode();
            $qrcodeModel->cid = $client->id;
            $qrcodeModel->scene_id = $scene_id;
            $qrcodeModel->type = $type;
            $qrcodeModel->type_id = 0;
            $qrcodeModel->remark = $remark;
            $qrcodeModel->ticket = $result->ticket;
            $qrcodeModel->url = $qrcode->url($result->ticket);
            $res = $qrcodeModel->save();
            if ($res) {
                return $this->ajaxMessage('添加成功');
            }
            return $this->ajaxError('添加失败');
        } else {
            $id = intval($request->input('id'));
            $qrcodeModel = WxQrcode::where('id', $id)->where('cid', $client->id)->first();
            if (!$qrcodeModel) {
                return $this->ajaxError('未找到该条信息');
            }
            if ($qrcodeModel->scene_id != $scene_id) {
                return $this->ajaxError('未找到该条信息');
            }
            $qrcodeModel->remark = $remark;
            $res = $qrcodeModel->save();
            if ($res) {
                return $this->ajaxMessage('修改成功');
            }
            return $this->ajaxError('修改失败');
        }
    }

    public function destroy($id)
    {
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $client = Auth::guard('company')->user();
        $qrocde = WxQrcode::where('id', $id)->where('cid', $client->id)->first();
        if (!$qrocde) {
            return $this->ajaxError('未找到该条信息');
        }
        $qrocde->delete();
        return $this->ajaxMessage('删除成功');
    }

    private function getFields($data = '')
    {
        $types = WxQrcode::getTypeArr();
        $fields = [
            'scene_id' => [
                'input' => 'text',
                'label' => '场景ID',
                'required' => 'required',
                'default' => '',
                'readonly' => ''
            ],
            'type' => [
                'input' => 'select',
                'label' => '二维码类型',
                'required' => 'required',
                'default' => '',
                'values' => $types,
            ],
            'remark' => [
                'input' => 'text',
                'label' => '描述',
                'required' => 'required',
                'default' => '',
            ],
        ];
        if ($data) {
            foreach ($fields as $k => $v) {
                $fields[$k]['default'] = $data->$k;
                if (array_key_exists('readonly', $v)) {
                    $fields[$k]['readonly'] = 'readonly';
                }
            }
        }
        return $fields;
    }
}
