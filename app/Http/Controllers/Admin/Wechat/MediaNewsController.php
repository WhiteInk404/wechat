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
use App\Services\ArticleImageUpload;

class MediaNewsController extends BaseController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $mcid = $request->input('mcid');
        if (!$mcid) {
            $news = Media::where('type', Media::TYPE_NEWS)
                ->orderBy('id', 'desc')
                ->paginate(12);
        } else {
            $news = Media::where('type', Media::TYPE_NEWS)
                ->where('mcid', intval($mcid))
                ->orderBy('id', 'desc')
                ->paginate(12);
        }

        $cates = MediaCategory::where('type', Media::TYPE_NEWS)->get();
        $data = [
            'news' => $news,
            'cates' => $cates,
            'type' => 'news',
            'mcid' => $mcid,
            'request' => $request->all(),
        ];
        return view('admin.wechat.media_news_index', $data);
    }

    public function create()
    {
        return view('admin.wechat.media_news_edit');
    }

    public function edit($id)
    {
        $id = intval($id);
        $news = Media::where('type', Media::TYPE_NEWS)
            ->where('id', $id)
            ->first();
        if (!$news) {
            return $this->ajaxError('没有找到该条信息');
        }
        $data = [
            'id' => $id,
            'data' => $news
        ];
        return view('admin.wechat.media_news_edit', $data);
    }

    /** 上传图片
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        if (!$request->hasFile('files')) {
            return $this->ajaxError('没有上传信息');
        }
        $file_size = $request->file('files')->getClientSize();
        if ($file_size > 2097152) {
            return $this->ajaxError('图片大小超过限制，请重新选择');
        }
        $file_type = $request->file('files')->getClientMimeType();
        if ($request->file('files')->isValid()) {
            $path = '/upload/medias/images/' . date('Ymd') . '/';
            $name = $request->file('files')->getClientOriginalName();
            $real_name = md5(uniqid(rand(), TRUE)) . '.' . $request->file('files')->guessClientExtension();
            $request->file('files')->move($path, $real_name);
        } else {
            return $this->ajaxError('上传失败，请重试');
        }
        $media = new Media();
        $media->mcid = 0;
        $media->name = $name;
        $media->type = Media::TYPE_IMAGE;
        $media->content = [
            'url' =>  asset($path . $real_name),
            'size' => $file_size,
            'type' => $file_type
        ];
        $res = $media->save();
        if ($res) {
            return $this->ajaxMessage(ltrim($path . $real_name, '.'));
        }
        return $this->ajaxError('图片上传出错');
    }

    public function store(Request $request)
    {
        $id = trim($request->input('id'));
        $cellData = $request->input('cellData');
        if (!$cellData) {
            return $this->ajaxError('保存失败，请重试');
        }
        if ($id) {
            $media = Media::where('id', $id)->first();
        } else {
            $media = new Media();
        }
        $media->mcid = 0;
        $media->name = '';
        $media->type = Media::TYPE_NEWS;
        $media->content = [
            'media_id' => '',
            'articles' => $cellData
            ];
        $res = $media->save();
        if ($res) {
            return $this->ajaxMessage('保存成功');
        }
        return $this->ajaxError('保存失败，请重试');
    }

    /**
     * 文章预览
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show(Request $request, $id)
    {
        $key = trim($request->input('key'));
        $news = Media::where('type', Media::TYPE_NEWS)
            ->where('id', $id)
            ->first();
        if (!$news) {
            return $this->showErrorAdmin('未找到该条信息', '/admin/wx/media/article');
        }
        $data = [
            'article' => $news,
            'key' => $key
        ];
        return view('admin.wechat.media_news_show', $data);
    }

    public function destroy($id)
    {
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $res = Media::where('type', Media::TYPE_NEWS)
        ->where('id', $id)
        ->delete();
        if ($res) {
            return $this->ajaxMessage('删除成功');
        }
        return $this->ajaxError('删除失败');
    }

    public function imageChoose(Request $request)
    {
        $images = Media::where('type', Media::TYPE_IMAGE)
            ->orderBy('id', 'DESC')
            ->paginate(6);
        $response = [
            'pagination' => [
                'total' => $images->total(),
                'per_page' => $images->perPage(),
                'current_page' => $images->currentPage(),
                'last_page' => $images->lastPage(),
                'from' => $images->firstItem(),
                'to' => $images->lastItem()
            ],
            'imageData' => $images
        ];
        return $this->ajaxMessage($response);
    }

    public function uploadByUE(Request $request)
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

    private function uploadByUEError($request, $message)
    {
        $result = json_encode(['state' => $message]);
        if ($request->has('callback')) {
            if (preg_match("/^[\w_]+$/", $request->input('callback'))) {
                $result =  htmlspecialchars($request->input('callback')) . '(' . $result . ')';
            }
        }
        return $result;
    }

    public function getList(Request $request)
    {
        $id = intval($request->input('id', 0));
        if ($id) {
            $news = Media::where('id', $id)
                ->where('type', Media::TYPE_NEWS)
                ->first();
            if (!$news) {
                return $this->ajaxError('Not Found');
            }
            $content = $news->content;
            if (!property_exists($content, 'articles')) {
                return $this->ajaxError('Not Found');
            }
            $articles = [];
            foreach ($content->articles as $v) {
                if (!property_exists($v, 'title')) {
                    return $this->ajaxError('Not Found');
                }
                if (!property_exists($v, 'image_url')) {
                    return $this->ajaxError('Not Found');
                }
                $articles[] = [
                    'title' => $v->title,
                    'imgurl' => $v->image_url
                ];
            }
            $data = [
                'mid' => $news->id,
                'articles' => $articles
            ];
            return $this->ajaxMessage($data);
        } else {
            $medias = Media::where('type', Media::TYPE_NEWS)
                ->orderBy('id', 'desc')
                ->paginate(6);
            $data = [];
            foreach ($medias as $news) {
                $content = $news->content;
                if (!property_exists($content, 'articles')) {
                    return $this->ajaxError('No Found');
                }
                $articles = [];
                foreach ($content->articles as $v) {
                    if (!property_exists($v, 'title')) {
                        return $this->ajaxError('No Found');
                    }
                    if (!property_exists($v, 'image_url')) {
                        return $this->ajaxError('No Found');
                    }
                    $articles[] = [
                        'title' => $v->title,
                        'imgurl' => $v->image_url
                    ];
                }
                $data['news'][] = [
                    'mid' => $news->id,
                    'articles' => $articles
                ];
            }
            $data['pagination'] = [
                'total' => $medias->total(),
                'per_page' => $medias->perPage(),
                'current_page' => $medias->currentPage(),
                'last_page' => $medias->lastPage(),
                'from' => $medias->firstItem(),
                'to' => $medias->lastItem()
            ];
            return $this->ajaxMessage($data);
        }
        return $this->ajaxError('参数错误');
    }

}
