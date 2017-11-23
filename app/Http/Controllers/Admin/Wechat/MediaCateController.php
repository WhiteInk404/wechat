<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Models\MediaCategory;
use App\Models\Media;
use App\Services\Qnupload;
use Auth;

class MediaCateController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request, $type)
    {
        $media_type = $this->changeType($type);
        $cates = MediaCategory::where('type', '=',  $media_type)
            ->orderBy('id', 'desc')
            ->get();
        $data = [
            'cates' => $cates,
            'type' => $type
        ];
        return view('admin.wechat.media_cate_index', $data);
    }

    public function create($type)
    {
        $fields = $this->getCateFields();
        $data = [
            'action' => 'add',
            'id' => 0,
            'type' => $type,
            'fields' => $fields
        ];
        return view('admin.wechat.media_cate_edit', $data);
    }

    public function edit($id, $type)
    {
        $media_type = $this->changeType($type);
        $cate = MediaCategory::find($id);
        if (!$cate) {
            return $this->showErrorAdmin('未找到该条信息', '/admin/wx/media/'.$type.'/cate');
        }
        $fields = $this->getCateFields($cate);
        $data = [
            'action' => 'edit',
            'id' => $id,
            'type' => $type,
            'fields' => $fields
        ];
        return view('admin.wechat.media_cate_edit', $data);
    }

    public function store(Request $request, $type)
    {
        $name = trim($request->input('name'));
        $media_type = $this->changeType($type);
        $cate = MediaCategory::where('name', $name)
            ->where('type', $media_type)
            ->first();
        $action = $request->input('action');
        if ($action == 'add') {
            if ($cate) {
                return $this->ajaxError('分类名称已经存在');
            }
            $cateModel = new MediaCategory();
            $cateModel->type = $media_type;
            $cateModel->name = $name;
            $res = $cateModel->save();
            if ($res) {
                return $this->ajaxMessage('添加分类成功');
            }
            return $this->ajaxError('添加分类失败');
        } else {
            $id = intval($request->input('id'));
            if ($cate && $cate->id != $id) {
                return $this->ajaxError('分类名称已经存在');
            } else if ($cate) {
                return $this->ajaxMessage('修改分类成功');
            }
            $cateModel = MediaCategory::find($id);
            $cateModel->name = $name;
            $res = $cateModel->save();
            if ($res) {
                return $this->ajaxMessage('修改分类成功');
            }
            return $this->ajaxError('修改分类失败');
        }
    }

    private function getCateFields($data = '')
    {
        $fields = [
            'name' => [
                'input' => 'text',
                'label' => '分类名称',
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

    public function destroy($id)
    {
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $res = MediaCategory::where('id', '=', $id)->delete();
        if ($res) {
            return $this->ajaxMessage('删除成功');
        }
        return $this->ajaxError('删除失败');
    }

    public function changeType($type)
    {
        switch($type) {
            case 'image':
                $cate = Media::TYPE_IMAGE;
                break;
            case 'article':
                $cate = Media::TYPE_ARTICLE;
                break;
            case 'news':
                $cate = Media::TYPE_NEWS;
                break;
            case 'voice':
                $cate = Media::TYPE_VOICE;
                break;
            default:
                $cate = 0;
        };
        return $cate;
    }
}
