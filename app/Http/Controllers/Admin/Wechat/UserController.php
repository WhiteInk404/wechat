<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;

use App\Models\WxUser;
use App\Models\Tag;
use App\Models\UserTag;
use App\Models\CreditType;
use App\Models\CreditRecord;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use Session;
use View;
use Auth;

/**
 * 后台用户管理
 * Class UserController
 * @package App\Http\Controllers\Admin\Wechat
 */
class UserController extends BaseController
{
    /**
     * 用户管理首页
     * @return View
     */
    public function index()
    {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $users = WxUser::where('cid', $cid)->paginate(20);
        $data = [
            'users' => $users
        ];
        return view('admin.wechat.user_index',$data);
    }


    /**
     * 修改
     * @param $id 要修改的id
     * @return mixed|View
     */
    public function edit($id)
    {
        if (!$id) {
            return $this->showErrorAdmin('ID不存在', '/company/wx/user');
        }
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $user = WxUser::where('id', $id)->where('cid', $cid)->first();
        if (!$user) {
            return $this->showErrorAdmin('未找到该条信息', '/company/wx/user');
        }
        $data['action'] = 'edit';
        $data['id'] = $id;
        $data['fields'] = $this->getFields($user);
        $data['qrcode'] = $user;
        return view('admin.wechat.user_edit', $data);
    }

    public function show(Request $request, $type)
    {
        if ($type == 'tag') {
            $wxUid = $request->input('wxUid');
            $client = Auth::guard('company')->user();
            $cid = $client->id;
            $nickname = WxUser::where('cid', $cid)->where('id', $wxUid)->value('nickname');
            $tags = Tag::where('cid', $cid)->pluck('name', 'name');
            $fields = [
                'nickname' => [
                    'input' => 'text',
                    'label' => '用户名',
                    'required' => 'required',
                    'default' => $nickname,
                    'readonly' => 'readonly'
                ],
            ];
            $tids = UserTag::where('cid', $cid)->where('wx_uid', $wxUid)->pluck('tid');
            $tags = Tag::where('cid', $cid)->whereIn('id', $tids)->get();
            $data = [
                'wxUid' => $wxUid,
                'fields' => $fields,
                'tags' => $tags,
            ];
            return view('admin.wechat.user_tag', $data);
        }
    }

    /**
     * 修改执行
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $action = $request->input('action');
        if ($action == 'tag') {
            $client = Auth::guard('company')->user();
            $cid = $client->id;
            $wxUid = $request->input('wxUid');
            $wxUser = WxUser::find($wxUid);
            $wxOpenid = $wxUser->openid;
            $uid = $wxUser->uid;
            $tags = $request->input('tags');
            if (!$tags) {
                $tags = [];
            }
            $tids = Tag::where('cid', $cid)->whereIn('name', $tags)->pluck('id');
            $deleteTids =  UserTag::where('cid', $cid)
                ->where('wx_uid', $wxUid)
                ->whereNotIn('tid', $tids)
                ->pluck('tid');
            foreach ($deleteTids as $tid) {
                $tag = Tag::where('cid', $cid)->where('id', $tid)->first();
                $count = $tag->count - 1;
                if ($count < 1) {
                    $count = 0;
                }
                $tag->count = $count;
                $result = $tag->save();
                if (!$result) {
                    return $this->ajaxError('保存失败');
                }
            }
            UserTag::where('cid', $cid)
                ->where('wx_uid', $wxUid)
                ->whereNotIn('tid', $tids)
                ->delete();
            foreach ($tags as $tag) {
                $tid = Tag::where('cid', $cid)->where('name', $tag)->value('id');
                if (!$tid) {
                    $tid = Tag::insertGetId(['name' => $tag, 'cid' => $cid]);
                }
                if(!$tid) {
                    return $this->ajaxError('保存失败');
                }
                $userTagId = UserTag::where('cid', $cid)
                    ->where('wx_uid', $wxUid)
                    ->where('tid', $tid)
                    ->value('id');
                if(!$userTagId) {
                    $userTagId = UserTag::insertGetId([
                        'cid' => $cid,
                        'uid' => $uid,
                        'tid' => $tid,
                        'wx_uid' => $wxUid,
                        'wx_openid' => $wxOpenid,
                    ]);
                    if (!$userTagId) {
                        return $this->ajaxError('保存失败');
                    }
                    $tag = Tag::where('cid', $cid)->where('id', $tid)->first();
                    $tag->count = $tag->count + 1;
                    $result = $tag->save();
                    if (!$result) {
                        return $this->ajaxError('保存失败');
                    }
                }
            }
            return $this->ajaxMessage('保存成功');
        }

        $remark = $request->input('remark');
        $id = $request->input('id');
        $manage = WxUser::find($request->input('id'));
        $manage->remark = $remark;
        $manage->save();
        if ($manage) {
            return $this->ajaxMessage('修改成功');
        }
        return $this->ajaxError('修改失败');
    }


    public function creditRecord(Request $request)
    {
        $client = Auth::guard('company')->user();
        $uid = intval($request->input('uid'));
        if (!$uid) {
            return 'User is not exist!';
        }
        $wxUser = wxUser::where('cid', $client->id)
            ->where('uid', $uid)
            ->first();
        if (!$wxUser) {
            return 'User is not exist!';
        }
        $creditRecords = CreditRecord::where('cid', $client->id)
            ->where('uid', $uid)
            ->paginate(20);
        $ctids = [];
        foreach ($creditRecords as $creditRecord) {
            if (!in_array($creditRecord->ctid, $ctids)) {
                $ctids[] = $creditRecord->ctid;
            }
        }
        $creditTypes = CreditType::where('cid', $client->id)
            ->whereIn('id', $ctids)
            ->get();
        $types = [];
        foreach ($creditTypes as $v) {
            $types[$v->id] = $v;
        }
        foreach ($creditRecords as $k => $v) {
            $type = $types[$v->ctid];
            if ($type->is_add) {
                $credit = $v->credit;
            } else {
                $credit = '-' . $v->credit;
            }
            $v->newCredit = $credit;
            $v->afertCredit = $v->updated_credit;
            $v->name = $type->name;
            $v->source = $type->source;
            $v->action = $type->action;
            $creditRecords[$k] = $v;
        }
        $data = [
            'list' => $creditRecords,
            'nickname' => $wxUser->nickname
        ];
        return view('admin.wechat.user_credit_record', $data);
    }
    private function getFields($data = '')
    {
        $fields = [
            'nickname' => [
                'input' => 'text',
                'label' => '名称',
                'required' => 'required',
                'default' => '',
                'readonly' => ''
            ],
            'remark' => [
                'input' => 'text',
                'label' => '备注',
                'required' => '',
                'default' => '',
            ]
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
