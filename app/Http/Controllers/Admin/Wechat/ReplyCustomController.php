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

class ReplyCustomController extends BaseController
{
    public function index(Request $request)
    {
        $replys = ReplyRule::where('type', ReplyRule::TYPE_CUSTOM)
            ->orderBy('id', 'desc')
            ->paginate(5);
        return view('admin.wechat.reply_custom_index', ['replys' => $replys]);
    }

    public function create(Request $request)
    {
        $data['actionType'] = 'custom';
        $data['actionUrl'] = '/admin/wx/reply/custom';
        $data['reply'] = '';
        return view('admin.wechat.wx_subscribe_index', $data);
    }

    public function show($id)
    {
        $reply = ReplyRule::where('id', $id)
            ->where('type', ReplyRule::TYPE_CUSTOM)
            ->first();
        $data['actionType'] = 'custom';
        $data['actionUrl'] = '/company/wx/reply/custom';
        $data['reply'] = $reply;
        return view('admin.wechat.wx_subscribe_index', $data);
    }


    public function store(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));
        if (!$keyword) {
            return $this->ajaxError('关键词不能为空');
        }
        $matchType = intval($request->input('matchType', 0));
        if (!$matchType) {
            return $this->ajaxError('请选择匹配类型');
        }
        if ($matchType != 1 && $matchType != 2) {
            return $this->ajaxError('请选择匹配类型');
        }
        $replyType = intval($request->input('replyType', 0));
        $content = $request->input('content', '');
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
        $status = intval($request->input('status', 0));
        $id = intval($request->input('id', 0));
        $reply = ReplyRule::where('type', ReplyRule::TYPE_CUSTOM)
            ->where('keyword', $keyword)
            ->first();
        if (!$id) {
            if ($reply) {
                return $this->ajaxError('关键词已经存在');
            }
            $replyModel = new ReplyRule();
        } else {
            if ($reply && $id != $reply->id) {
                return $this->ajaxError('关键词已经存在');
            }
            $replyModel = ReplyRule::find($id);
        }
        $replyModel->keyword = $keyword;
        $replyModel->type = ReplyRule::TYPE_CUSTOM;
        $replyModel->match_type = $matchType;
        $replyModel->reply_type = $replyType;
        if ($replyType == ReplyRule::REPLY_TYPE_TEXT) {
            $replyModel->mid = 0;
            $replyModel->content = $content;
        } else {
            $replyModel->mid = $mid;
            $replyModel->content = '';
        }
        $replyModel->status = $status;
        $replyModel->save();
        return $this->ajaxMessage('保存成功');
    }

    public function destroy($id)
    {
        $id = intval($id);
        $reply = ReplyRule::find($id);
        if (!$reply) {
            return $this->ajaxError('未找到该条信息');
        }
        if ($reply->type != ReplyRule::TYPE_CUSTOM) {
            return $this->ajaxError('未找到该条信息');
        }
        $reply->delete();
        return $this->ajaxMessage('删除成功');
    }
}
