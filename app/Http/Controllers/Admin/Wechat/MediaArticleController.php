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

class MediaArticleController extends BaseController
{
    /**
     * 文章列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $mcid = $request->input('mcid', 0);
        if (!$mcid) {
            $articles = Media::where('type', '=', Media::TYPE_ARTICLE)
                ->orderBy('id', 'desc')
                ->paginate(13);
        } else {
            $articles = Media::where('type', '=', Media::TYPE_ARTICLE)
                ->where('mcid', '=', intval($mcid))
                ->orderBy('id', 'desc')
                ->paginate(13);
        }
        $cates = MediaCategory::where('type', '=', Media::TYPE_ARTICLE)->get();
        $data = [
            'articles' => $articles,
            'cates' => $cates,
            'type' => 'article',
            'mcid' => $mcid,
            'request' => $request->all()
        ];
        return view('admin.wechat.media_article_index', $data);
    }

    /**
     * 文章添加和编辑
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create(Request $request)
    {
        $cates = MediaCategory::where('type', MediaCategory::TYPE_ARTICLE)
            ->get();

        $data = [
            'action' => 'add',
            'id' => 0,
            'cates' => $cates
        ];

        return view('admin.wechat.media_article_edit', $data);
    }

    public function edit($id)
    {
        $id = intval($id);
        $article = Media::where('id', $id)
            ->where('type', Media::TYPE_ARTICLE)
            ->first();
        if (!$article) {
            return $this->showErrorAdmin('未找到该条信息', '/admin/wx/media/article');
        }
        $cates = MediaCategory::where('type', MediaCategory::TYPE_ARTICLE)
            ->get();
        $data = [
            'action' => 'edit',
            'id' => $id,
            'cates' => $cates,
            'article' => $article
        ];
        return view('admin.wechat.media_article_edit', $data);
    }

    /**
     * 文章预览
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $article = Media::where('id', $id)
            ->where('type', Media::TYPE_ARTICLE)
            ->first();
        if (!$article) {
            return $this->showErrorAdmin('未找到该条信息', '/admin/wx/media/article');
        }
        return view('admin.wechat.media_article_show', ['article' => $article]);
    }
    /**
     * 文章保存
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $name = trim($request->input('name'));
        $atime = trim($request->input('atime'));
        $mcid = intval($request->input('mcid', 0));
        if ($mcid) {
            $mediaCategory = MediaCategory::where('id', $mcid)
                ->where('type', MediaCategory::TYPE_ARTICLE)
                ->first();
            if (!$mediaCategory) {
                return $this->ajaxError('分类信息不存在');
            }
        }

        $action = $request->input('action');
        $content = trim($request->input('content'));
        if ($action == 'add') {
            $media = new Media();
        } else {
            $id = intval(trim($request->input('id', 0)));
            $media = Media::where('id', $id)
                ->where('type', Media::TYPE_ARTICLE)
                ->first();
            if (!$media) {
                return $this->ajaxError('编辑文章失败');
            }
        }
        $media->mcid = $mcid;
        $media->type = Media::TYPE_ARTICLE;
        $media->name = $name;
        $media->content = [
            'content' =>$content,
            'publish_date' => $atime,
        ];
        $media->save();
        return $this->ajaxMessage('添加文章成功');
    }

    /**
     * 文章删除
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
            ->where('type', Media::TYPE_ARTICLE)
            ->first();
        if (!$media) {
            return $this->ajaxError('未找到该条信息');
        }
        $media->delete();
        return $this->ajaxMessage('删除成功');

    }


}
