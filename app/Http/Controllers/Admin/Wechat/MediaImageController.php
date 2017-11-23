<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Models\MediaCategory;
use App\Models\Media;
use App\Services\Qnupload;

class MediaImageController extends BaseController
{

    /** 图片列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $mcid = $request->input('mcid', 0);
        if (!$mcid) {
            $images = Media::where('type', Media::TYPE_IMAGE)
                ->where('name', 'like', '%'.$request->input('keyword').'%')
                ->orderBy('id', 'desc')
                ->paginate(16);
        } else {
            $images = Media::where('type', Media::TYPE_IMAGE)
                ->where('mcid', intval($mcid))
                ->orderBy('id', 'desc')
                ->paginate(16);
        }
        $cates = MediaCategory::where('type', Media::TYPE_IMAGE)
            ->get();
        $data = [
            'images' => $images,
            'cates' => $cates,
            'type' => 'image',
            'mcid' => $mcid,
            'request' => $request->all(),
            'keyword' => $request->input('keyword')
        ];
        return view('admin.wechat.media_image_index', $data);
    }

    /** 编辑图片
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $image = Media::where('id', $id)
            ->where('type', Media::TYPE_IMAGE)
            ->first();
        if (!$image) {
            return $this->ajaxError('未找到该条信息');
        }
        $name = trim($request->input('name'));
        $mcid = intval(trim($request->input('mcid')));
        if ($mcid) {
            $mediaCategory = MediaCategory::where('id', $mcid)
                ->where('type', MediaCategory::TYPE_IMAGE)
                ->first();
            if (!$mediaCategory) {
                return $this->ajaxError('未找到分类信息');
            }
        }
        $image->name = $name;
        $image->mcid = $mcid;
        $image->save();
        return $this->ajaxMessage('编辑图片成功');
    }

    /** 图片删除
     * @param Request $request
     * @return mixed
     */
    public function destroy($id)
    {
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $id = intval($id);
        $media = Media::where('id', $id)
            ->where('type', Media::TYPE_IMAGE)
            ->first();
        if (!$media) {
            return $this->ajaxError('未找到该条信息');
        }
        $media->delete();
        return $this->ajaxMessage('删除成功');
    }

    /** 上传图片
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        if (!$request->hasFile('file')) {
            return $this->ajaxError('没有上传信息');
        }
        $file_size = $request->file('file')->getClientSize();
        if ($file_size > 2097152) {
            return $this->ajaxError('图片大小超过限制，请重新选择');
        }
        $file_type = $request->file('file')->getClientMimeType();
        if ($request->file('file')->isValid()){
            $path = '/uploads/medias/images/' . date('Ymd') . '/';
            $name = $request->file('file')->getClientOriginalName();
            $real_name = md5(uniqid(rand(), TRUE)). '.' . $request->file('file')->guessClientExtension();
            $request->file('file')->move(public_path() . $path, $real_name);
        } else {
            return $this->ajaxError('上传失败，请重试');
        }
        $media = new Media();
        $media->mcid = 0;
        $media->name = $name;
        $media->type = Media::TYPE_IMAGE;
        $media->content = [
            'url' => asset($path . $real_name),
            'size' => $file_size,
            'type' => $file_type
        ];
        $media->save();
        return $this->ajaxMessage(asset($path . $real_name));
    }

    public function getList(Request $request)
    {
        $id = intval($request->input('id', 0));
        if ($id) {
            $image = Media::where('id', $id)
                ->where('type', Media::TYPE_IMAGE)
                ->first();
            if (!$image) {
                return $this->ajaxError('No Found');
            }
            $data = [
                'mid' => $image->id,
                'name' => $image->name,
                'url' => $image->content->url,
                'size' => $image->content->size
            ];
            return $this->ajaxMessage($data);
        } else {
            $images = Media::where('type', Media::TYPE_IMAGE)
                ->orderBy('id', 'desc')
                ->paginate(12);
            $data = [];
            foreach ($images as $image) {
                $data['images'][] = [
                    'mid' => $image->id,
                    'name' => $image->name,
                    'url' => $image->content->url,
                    'size' => $image->content->size
                ];
            }
            $data['pagination'] = [
                'total' => $images->total(),
                'per_page' => $images->perPage(),
                'current_page' => $images->currentPage(),
                'last_page' => $images->lastPage(),
                'from' => $images->firstItem(),
                'to' => $images->lastItem()
            ];
            return $this->ajaxMessage($data);
        }
        return $this->ajaxError('参数错误');
    }


}
