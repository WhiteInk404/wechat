<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Models\Media;
use App\Models\WxMessage;
use App\Models\Tag;
use App\Models\WxQrcode;
use App\Models\WxConfig;
use App\Services\WechatSdkService;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Storage;
use EasyWeChat\Message\Article;
use Session;
use View;
use Auth;

class SendmassController extends BaseController
{
    protected $material;

    private function getMessageType()
    {
        $messageType = [
            WxMessage::TYPE_TEXT => '文本',
            WxMessage::TYPE_IMAGE => '图片',
            WxMessage::TYPE_NEWS => '图文'
        ];
        return $messageType;
    }

    private function getStatus()
    {
        $status = [
            WxMessage::STATUS_NEW => '未发送',
            WxMessage::STATUS_ING => '发送中',
            WxMessage::STATUS_SENDED => '已发送',
            WxMessage::STATUS_CANCELD => '取消发送'
        ];
        return $status;
    }


    public function index()
    {
        $client = Auth::guard('company')->user();
        $data['tags'] = Tag::where('cid', $client->id)->select('id', 'name')->get();
        $data['messageType'] = $this->getMessageType();
        $qrcodes = WxQrcode::where('cid', $client->id)->select('id', 'scene_id', 'remark')->get();
        $data['qrcodes'] = $qrcodes;
        $data['status'] = $this->getStatus();
        $data['wxMessages'] = WxMessage::where('cid', $client->id)->orderBy('id', 'desc')->paginate(10);
        $data['wxMessagesResult'] = count($data['wxMessages']);
        return view('admin.wechat.sendmass_index', $data);
    }

    public function store(Request $request)
    {
        $client = Auth::guard('company')->user();
        $type = intval($request->input('type', 0));
        if (!$type) {
            return $this->ajaxError('请选择消息类型');
        }
        $content = trim($request->input('content'));
        $mid = intval($request->input('mid', 0));
        if ($type == WxMessage::TYPE_TEXT && !$content) {
            return $this->ajaxError('请填写回复内容');
        } else if ($type != WxMessage::TYPE_TEXT && !$mid) {
            return $this->ajaxError('请选择回复内容');
        } else if ($type !=WxMessage::TYPE_TEXT && $mid) {
            $media = Media::where('id', $mid)
                ->where('cid', $client->id)
                ->where('type', $type)
                ->first();
            if (!$media) {
                return $this->ajaxError('请选择回复内容');
            }
        }
        $tagIds = $request->input('tags');
        $qrcodeIds = $request->input('qrcodes');
        if (!$tagIds && !$qrcodeIds) {
            return $this->ajaxError('标签或二维码至少选择一项');
        }
        $to = [];
        if ($tagIds) {
            $tagIds = array_values(array_map(function ($v) {
                return intval($v);
            }, $tagIds));
            $tags = Tag::where('cid', $client->id)
                ->whereIn('name', $tagIds)
                ->get();
            foreach ($tags as $v) {
                $to['tags'][$v->id] = $v->name;
            }
        }
        if ($qrcodeIds) {
            $qrcodeIds = array_values(array_map(function ($v) {
                return intval($v);
            }, $qrcodeIds));
            $wxQrcodes = WxQrcode::where('cid', $client->id)
                ->whereIn('id', $qrcodeIds)
                ->get();
            foreach ($wxQrcodes as $v) {
                $to['qrcodes'][$v->scene_id] = $v->remark;
            }
        }
        if (!$to) {
            return $this->ajaxError('标签或二维码至少选择一项');
        }
        $msgContent = '';
        $this->material = WechatSdkService::initByCid($client->id)->material;
        switch ($type) {
            case WxMessage::TYPE_TEXT:
                $msgContent = ['value' => $content];
                break;
            case WxMessage::TYPE_IMAGE:
                if (property_exists($media->content, 'media_id') && $media->content->media_id) {
                    $msgContent = [
                        'mid' => $media->id,
                        'media_id' => $media->content->media_id,
                        'media_url' => $media->content->media_url,
                        'url' => $media->content->url
                    ];
                } else {
                    $imageRemotePath = $media->content->url;
                    $url = $this->moveImage($imageRemotePath);
                    if (!$url) {
                        return $this->ajaxError('图片上传失败');
                    }
                    $res = $this->material->uploadImage($url);
                    if ($res) {
                        $msgContent = [
                            'mid' => $media->id,
                            'media_id' => $res->media_id,
                            'media_url' => $res->url,
                            'url' => $media->content->url
                        ];

                        $mediaContent = (array)$media->content;
                        $mediaContent['media_id'] = $res->media_id;
                        $mediaContent['media_url'] = $res->url;
                        $media->content = $mediaContent;
                        $media->save();
                    }
                }
                break;
            case WxMessage::TYPE_NEWS:
                if (property_exists($media->content, 'media_id') && $media->content->media_id) {
                    $msgContent = [
                        'mid' => $media->id,
                        'media_id' => $media->content->media_id
                    ];
                } else {
                    $mediaId = $this->syncWxNews($media);
                    if (!$mediaId) {
                        return $this->ajaxError('图文上传失败');
                    }
                    $msgContent = [
                        'mid' => $media->id,
                        'media_id' => $mediaId
                    ];
                }
            default:
                break;
        }

        if (!$msgContent) {
            return $this->ajaxError('请选择回复内容');
        }
        $wxMessage = new WxMessage();
        $wxMessage->cid = $client->id;
        $wxMessage->to = $to;
        $wxMessage->type = $type;
        $wxMessage->content = $msgContent;
        $wxMessage->status = WxMessage::STATUS_NEW;
        $wxMessage->save();
        return $this->ajaxMessage('创建成功');
    }

    public function destroy($id)
    {
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $client = Auth::guard('company')->user();
        $wxMessage = WxMessage::where('id', $id)
            ->where('cid', $client->id)
            ->first();
        if (!$wxMessage) {
            return $this->ajaxError('未找到该条信息');
        }
        if ($wxMessage->status == WxMessage::STATUS_ING) {
            return $this->ajaxError('该条信息无法删除');
        }
        $wxMessage->delete();
        return $this->ajaxMessage('删除成功');
    }

    public function update(Request $request, $id)
    {
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $status = $request->input('status');
        $client = Auth::guard('company')->user();
        $wxMessage = WxMessage::where('id', $id)
            ->where('cid', $client->id)
            ->first();
        if (!$wxMessage) {
            return $this->ajaxError('未找到该条信息');
        }
        if ($status == 'play') {
            if ($wxMessage->status != WxMessage::STATUS_CANCELD) {
                return $this->ajaxError('该条信息无法重新发送');
            }
            $wxMessage->status = WxMessage::STATUS_NEW;
            $wxMessage->save();
            return $this->ajaxMessage('修改状态成功');
        }
        if ($status == 'cancle') {
            if ($wxMessage->status != WxMessage::STATUS_NEW) {
                return $this->ajaxError('该条信息无法取消发送');
            }
            $wxMessage->status = WxMessage::STATUS_CANCELD;
            $wxMessage->save();
            return $this->ajaxMessage('修改状态成功');
        }
        return $this->ajaxError('参数错误');
    }

    private function syncWxNews($media)
    {
        $content = $media->content;
        if (!property_exists($content, 'articles') || !$content->articles) {
            return false;
        }
        $articles = [];
        $uploadArticles = [];
        foreach ($content->articles as $k => $v) {
            if (!$v->thumb_media_id) {
                if (!$v->image_url) {
                    return false;
                }
                $url = $this->moveImage($v->image_url);
                if (!$url) {
                    return false;
                }
                $res = $this->material->uploadThumb($url);
                if (!$res) {
                    return false;
                }
                $thumbMediaId = $res->media_id;
            } else {
                $thumbMediaId = $v->thumb_media_id;
            }
            $article = [
                'title' => $v->title,
                'thumb_media_id' => $thumbMediaId,
                'author' => $v->author,
                'digest' => $v->digest,
                'show_cover_pic' => $v->show_cover_pic,
                'content' => $v->content,
                'content_source_url' => $v->content_source_url,
            ];
            $uploadArticles[] = new Article($article);
            $article['image_url'] = $v->image_url;
            $articles[] = $article;
        }
        if (!$uploadArticles) {
            return false;
        }
        $res = $this->material->uploadArticle($uploadArticles);
        if (!$res) {
            return false;
        }
        $mediaId = $res->media_id;
        // 更新数据库
        $media->content = [
            'media_id' => $mediaId,
            'articles' => $articles
        ];
        $media->save();
        return $mediaId;
    }

    private function moveImage($imageRemotePath)
    {
        $imageLocalPath = 'media/' . md5($imageRemotePath) . '.jpg';
        Storage::put($imageLocalPath, file_get_contents($imageRemotePath));
        $exists = Storage::exists($imageLocalPath);
        if (!$exists) {
            return false;
        }
        $url = storage_path('app') . '/' . $imageLocalPath;
        return $url;
    }
}