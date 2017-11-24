<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;

use App\Models\ReplyRule;
use App\Models\Media;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use Session;
use View;
use Auth;

class ReplyNomatchController extends BaseController
{
    public function __construct()
    {
        //parent::__construct();
        $this->client = Session::get('client_info');
    }

    public function index(Request $request)
    {
        $data['actionType'] = 'nomatch';
        $data['actionUrl'] = '/admin/wechat/reply/nomatch';
        $reply = ReplyRule::where('keyword', ReplyRule::KEYWORD_NOMATCH)
            ->where('type', ReplyRule::TYPE_BUILDIN)
            ->first();
        $data['reply'] = $reply;
        return view('admin.wechat.wx_subscribe_index', $data);
    }

    public function store(Request $request)
    {
        $replyType = intval($request->input('replyType', 0));
        $content = $request->input('content', '');
        $status = intval($request->input('status', 0));
        $mid = intval($request->input('mid', 0));
        if (!$replyType) {
            return $this->ajaxError('请选择消息类型');
        }
        if ($replyType == ReplyRule::REPLY_TYPE_TEXT && !$content) {
            return $this->ajaxError('请填写回复内容');
        } else if ($replyType != ReplyRule::REPLY_TYPE_TEXT) {
            if (!$mid) {
                return $this->ajaxError('请选择回复素材');
            }
            $media = Media::where('id', $mid)->where('type', $replyType)->first();
            if (!$media) {
                return $this->ajaxError('请选择回复素材');
            }
        }

        $reply = ReplyRule::firstOrCreate([
            'keyword' => ReplyRule::KEYWORD_NOMATCH,
            'type' => ReplyRule::TYPE_BUILDIN
        ]);
        $reply->match_type = ReplyRule::MATCH_TYPE_COMPLETE;
        $reply->reply_type = $replyType;
        if ($replyType == ReplyRule::REPLY_TYPE_TEXT) {
            $reply->mid = 0;
            $reply->content = $content;
        } else {
            $reply->mid = $mid;
            $reply->content = '';
        }
        $reply->status = $status;
        $reply->save();
        return $this->ajaxMessage('保存成功');
    }
}
