<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\ReplyRule;
use App\Models\WxUser;
use App\Services\WechatSdkService;
use EasyWeChat\Message\Material;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    protected $app;
    protected $openid;

    /**
     * 接入微信公众号
     * @return mixed
     */
    public function handle()
    {
        $this->app = WechatSdkService::init();
        $server = $this->app->server;

        $server->setMessageHandler(function ($message) {
            if ($message) {
                $method = Str::camel('handle_' . $message->MsgType);
                if (method_exists($this, $method)) {
                    return call_user_func_array([$this, $method], [$message]);
                }
            }
        });
        $response = $server->serve();
        // 将响应输出
        return $response;
    }

    // 文本消息
    public function handleText($message)
    {
        if ($message) {
            $this->openid = $message->FromUserName;
            $keyword = $message->Content;
            return $this->reply($keyword, ReplyRule::TYPE_CUSTOM);
        }
    }

    // 图片消息
    public function handleImage($message)
    {
        if ($message) {
            $this->openid = $message->FromUserName;
        }
    }

    // 语音消息
    public function handleVoice($message)
    {
        if ($message) {
            $this->openid = $message->FromUserName;
        }
    }

    // 视频消息
    public function handleVideo($message)
    {
        if ($message) {
            $this->openid = $message->FromUserName;
        }
    }

    // 小视频消息
    public function handleShortvideo($message)
    {
        if ($message) {
            $this->openid = $message->FromUserName;
        }
    }

    // 地理位置
    public function handleLocation($message)
    {
        if ($message) {
            $this->openid = $message->FromUserName;
        }
    }

    // 链接
    public function handleLink($message)
    {
        if ($message) {
            $this->openid = $message->FromUserName;
        }
    }

    // 事件
    public function handleEvent($message)
    {
        if ($message) {
            $this->openid = $message->FromUserName;
            $method = Str::camel('handle_event_' . strtolower($message->Event));
            if (method_exists($this, $method)) {
                $res = call_user_func_array([$this, $method], [$message]);
                if ($res) {
                    return $res;
                }
            }
        }
    }

    // 关注事件
    private function handleEventSubscribe($message)
    {
        $this->openid = $message->FromUserName;
        $sceneId = 0;
        if (property_exists($message, 'EventKey') && $message->EventKey) {
            $eventKey = strtoupper($message->EventKey);
            if (strpos($eventKey, 'QRSCENE_') !== false) {
                $sceneId = intval(str_replace('QRSCENE_', '', $eventKey));
            }
        }
        $wxAppUserService = $this->app->user;
        $wxAppUser = $wxAppUserService->get($this->openid);
        $wxUserModel = WxUser::where('openid', $this->openid)->first();
        if (!$wxUserModel) {
            $wxUserModel = new WxUser();
            $wxUserModel->openid = $this->openid;
            $wxUserModel->nickname = $wxAppUser->nickname;
            $wxUserModel->sex = $wxAppUser->sex;
            $wxUserModel->user_id = 0;
            $wxUserModel->subscribe = $wxAppUser->subscribe;
            $wxUserModel->avatar = $wxAppUser->headimgurl;
            $wxUserModel->city = $wxAppUser->city;
            $wxUserModel->province = $wxAppUser->province;
            $wxUserModel->language = $wxAppUser->language;
            $wxUserModel->subscribe_time = date('Y-m-d H:i:s', $wxAppUser->subscribe_time);
            $wxUserModel->save();
        }

        return $this->reply(ReplyRule::KEYWORD_SUBSCRIBE, ReplyRule::TYPE_BUILDIN);
    }

    // 取消关注事件
    private function handleEventUnsubscribe($message)
    {
        $wxUserModel = WxUser::where(['openid' => $this->openid])->first();
        if ($wxUserModel) {
            $wxUserModel->subscribe = 0;
            $wxUserModel->save();
        }
    }

    // 扫描事件
    private function handleEventScan($message)
    {

    }

    // 上报地理位置事件
    private function handleEventLocation($message)
    {

    }

    // 自定义菜单事件
    private function handleEventClick($message)
    {
        if ($message) {
            $keyword = $message->EventKey;
            return $this->reply($keyword, ReplyRule::TYPE_CUSTOM);
        }
    }

    // 点击菜单跳转链接时的事件
    private function handleEventView($message)
    {
        if ($message) {
            $url = $message->EventKey;
        }
    }

    private function reply($keyword, $type)
    {
        \Log::debug('sendmessage: ' . $keyword . '  ' . $type);
        $rules = ReplyRule::where('status', ReplyRule::STATUS_YES)->get();
        $nomatchRule = [];
        $completeRule = [];
        $fuzzyRule = [];
        foreach ($rules as $rule) {
            // 获取默认回复
            if ($rule->type == ReplyRule::TYPE_BUILDIN && $rule->keyword == ReplyRule::KEYWORD_NOMATCH) {
                $nomatchRule = $rule;
            }

            if ($type != $rule->type) {
                continue;
            }

            // 完全匹配
            if ($rule->match_type == ReplyRule::MATCH_TYPE_COMPLETE && $keyword == $rule->keyword && !$completeRule) {
                $completeRule = $rule;
            }
            // 模糊匹配
            if ($rule->match_type == ReplyRule::MATCH_TYPE_FUZZY && preg_match('/.*' . $rule->keyword . '.*/i', $keyword) && !$fuzzyRule) {
                $fuzzyRule = $rule;
            }
        }

        if (count($completeRule) > 0) {
            return $this->sendMessage($completeRule);
        } else if (count($fuzzyRule) > 0) {
            return $this->sendMessage($fuzzyRule);
        } else if (count($nomatchRule) > 0) {
            return $this->sendMessage($nomatchRule);
        }
        return '';
    }

    private function sendMessage($rule)
    {
        $replyType = $rule->reply_type;
        \Log::debug('sendmessage: ' . $replyType);
        $message = '';
        switch ($replyType) {
            case ReplyRule::REPLY_TYPE_TEXT:
                $message = $rule->content;
                break;
            case ReplyRule::REPLY_TYPE_NEWS:
                $media = Media::where('id', $rule->mid)
                    ->where('type', Media::TYPE_NEWS)
                    ->first();
                if ($media) {
                    if (property_exists($media->content, 'media_id') && $media->content->media_id) {
                        $mediaId = $media->content->media_id;
                    } else {
                        $mediaId = $this->syncWxNews($media);
                    }
                    if ($mediaId) {
                        $material = new Material('mpnews', $mediaId);
                        return $this->app->staff->message($material)->to($this->openid)->send();
                    }
                }
                break;
            case ReplyRule::REPLY_TYPE_IMAGE:
                $media = Media::where('id', $rule->mid)
                    ->where('type', Media::TYPE_IMAGE)
                    ->first();
                if ($media) {
                    if (property_exists($media->content, 'media_id') && $media->content->media_id) {
                        $mediaId = $media->content->media_id;
                    } else {
                        $mediaId = $this->syncWxImage($media);
                    }
                    if ($mediaId) {
                        $message = new Image(['media_id' => $mediaId]);
                    }
                }
                break;
            default:
                break;
        }
        return $message;
    }

    private function syncWxNews($media)
    {
        $content = $media->content;
        if (!property_exists($content, 'articles') || !$content->articles) {
            return false;
        }
        $articles = [];
        $uploadArticles = [];
        $material = $this->app->material;
        foreach ($content->articles as $k => $v) {
            if (!$v->thumb_media_id) {
                if (!$v->image_url) {
                    return false;
                }
                $url = $this->moveImage($v->image_url);
                if (!$url) {
                    return false;
                }
                $res = $material->uploadThumb($url);
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
        $res = $material->uploadArticle($uploadArticles);
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

    private function syncWxImage($media)
    {
        $url = $this->moveImage($media->content->url);
        if (!$url) {
            return false;
        }
        $material = $this->app->material;
        $res = $material->uploadImage($url);
        if (!$res) {
            return false;
        }
        $mediaId = $res->media_id;
        $content = (array)$media->content;
        $content['media_id'] = $mediaId;
        $content['media_url'] = $res->url;
        $media->content = $content;
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
