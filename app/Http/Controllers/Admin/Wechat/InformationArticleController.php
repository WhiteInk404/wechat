<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;

use App\Models\WxUser;
use App\Models\Tag;
use App\Models\UserTag;
use App\Models\CreditType;
use App\Models\CreditRecord;
use App\Models\InformationArticle;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use Session;
use View;
use Auth;

/**
 * 专家资讯管理
 * Class UserController
 * @package App\Http\Controllers\Admin\Wechat
 */
class InformationArticleController extends BaseController
{
    /**
     * 专家资讯首页
     * @return View
     */
    public function index()
    {
        $InformationArticles = InformationArticle::paginate(15);
        return view('admin.wechat.information_article_index', compact( 'InformationArticles'));
    }


    /**
     * 修改
     * @param $id 要修改的id
     * @return mixed|View
     */
    public function edit(Request $request, $id)
    {
        $id = intval($id);
        if($id < 0) {
            return $this->showError('未找到该页面');
        }elseif($id == 0) {
            $action = 'add';
            return view('admin.wechat.information_article_edit', compact('action', 'id'));
        }else {
            $action = 'edit';
            $InformationArticle = InformationArticle::findOrFail($id);
            return view('admin.wechat.information_article_edit', compact('action', 'InformationArticle', 'id'));
        }
    }


    /**
     * 修改执行
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
       $id = $request->input('id');
       $title = $request->input('title');
       $status = $request->input('status');
       $image = asset($request->input('image'));
       $content = $request->input('content');
       $readNums = $request->input('read_nums');
       if ($id == 0) {
           $InformationArticle = new InformationArticle();
       } else {
           $InformationArticle = InformationArticle::findOrFail($id);
       }
        $InformationArticle->title = $title;
        $InformationArticle->image = $image;
        $InformationArticle->status = $status;
        $InformationArticle->content = $content;
        $InformationArticle->read_nums = $readNums;
        $InformationArticle->save();
        if ($id == 0) {
            return $this->ajaxMessage('添加成功');
        }else {
            return $this->ajaxMessage('编辑成功');
        }
    }

    public function destroy($id)
    {
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $id = intval($id);
        $InformationArticle = InformationArticle::findOrFail($id);
        $result = $InformationArticle->delete();
        if ($result) {
            return $this->ajaxMessage('删除成功');
        } else {
            return $this->ajaxError('删除失败');
        }
    }

    public function upload(Request $request)
    {
        $con = '{
            "imageActionName": "uploadimage",
            "imageFieldName": "files", 
            "imageMaxSize": 2048000, 
            "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
            "imageCompressEnable": true,
            "imageCompressBorder": 1600,
            "imageInsertAlign": "none",
            "imageUrlPrefix": "",
            "imagePathFormat": ""
        }';

        $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $con), true);
        switch ($request->input('action')) {
            case 'config':
                $result = $config;
                break;
            case 'uploadimage':
                if (!$request->hasFile('files')) {
                    return $this->uploadByUEError($request, '没有上传信息');
                }
                $file_size = $request->file('files')->getClientSize();
                if ($file_size > 2048000) {
                    return $this->uploadByUEError($request, '图片大小超过限制，请重新选择');
                }
                $file_type = $request->file('files')->getClientMimeType();
                if ($request->file('files')->isValid()) {
                    $path = '/uploads/UEditor/images/' . date('Ymd') . '/';
                    $name = $request->file('files')->getClientOriginalName();
                    $real_name = md5(uniqid(rand(), TRUE)) . '.' . $request->file('files')->guessClientExtension();
                    $request->file('files')->move(public_path() .$path, $real_name);
                } else {
                    return $this->uploadByUEError($request, '上传失败，请重试');
                }
                $url = asset(ltrim($path . $real_name, '.'));
                $result = [
                    "state" => 'SUCCESS',
                    "url" => $url,
                    "title" => trim(strrchr($url, '/'), '/'),
                    "original" => $name,
                    "type" => $file_type,
                    "size" => $file_size
                ];
                break;
        }
        return response()->json($result, 200);
    }
}
