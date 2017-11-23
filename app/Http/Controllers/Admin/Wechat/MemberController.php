<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;

use App\Models\WxUser;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserTag;
use App\Models\CreditType;
use App\Models\CreditRecord;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use Session;
use View;
use Excel;
use Auth;

/**
 * 后台会员管理
 * Class MemberController
 * @package App\Http\Controllers\Admin\Wechat
 */
class MemberController extends BaseController
{
    /**
     * 会员管理首页
     * @return View
     */
    public function index(Request $request)
    {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $userid = intval($request->input('uid'));
        $openid = $request->input('openid');
        $phone = intval($request->input('phone'));
        $realname = $request->input('realname');
        $usertag = $request->input('tag');
        $creditStart = intval($request->input('credit_start'));
        if ($creditStart < 0) {
            $creditStart = '';
        };
        $creditEnd =intval($request->input('credit_end'));
        if ($creditEnd && $creditEnd < $creditStart ) {
            return back()->with('info', '结束值必须大于开始值');;
        }
        $query = User::where('cid', $cid)->where('is_registered', 1);
        if ($userid) {
            $query = $query->where('id', $userid);
        }
        if ($openid) {
            $wxUser = WxUser::where('cid', $cid)->where('openid', $openid)->first();
            $uid = $wxUser->uid;
            $query = $query->where('id', $uid);
        }
        if ($phone) {
            $query = $query->where('phone', $phone);
        }
        if ($realname) {
            $query = $query->where('realname', $realname);
        }
        if ($usertag) {
            $tag = Tag::where('cid', $cid)->where('name', $usertag)->first();
            if ($tag) {
                $tid = $tag->id;
                $uids = UserTag::where('cid', $cid)->where('tid', $tid)->pluck('uid');
                $query = $query->whereIn('id', $uids);
            } else {
                $query = $query->where('id', 0);
            }

        }
        if ($creditStart) {
            $query = $query->where('credit', '>=', $creditStart);
        }
        if ($creditEnd) {
            $query = $query->where('credit', '<=', $creditEnd);
        }
        if ($creditStart && $creditEnd) {
            $query = $query->whereBetween('credit', [$creditStart, $creditEnd]);
        }
        $members = $query->paginate(20);
        $openids = [];
        $subscribes = [];
        $memberTags = [];
        foreach ($members as  $member) {
            $userTags = UserTag::where('cid', $cid)->where('uid', $member->id)->get();
            $wxUsers = WxUser::where('cid', $cid)->where('uid', $member->id)->get();
            foreach ($userTags as $userTag) {
                $tag = Tag::where('cid', $cid)->where('id', $userTag->tid)->first();
                $memberTags[$member->id][] = $tag->name;
            };
            foreach ($wxUsers as $wxUser) {
                $openids[$member->id]['openid'] = $wxUser->openid;
                $subscribes[$member->id]['subscribe'] = $wxUser->subscribe;
            }
        }
            $data = [
                'members' => $members,
                'openids' => $openids,
                'openid' => $openid,
                'subscribes' => $subscribes,
                'memberTags' => $memberTags,
                'phone' => $phone,
                'userid' => $userid,
                'realname' => $realname,
                'usertag' => $usertag,
                'creditStart' => $creditStart,
                'creditEnd' => $creditEnd,
            ];
        return view('admin.wechat.member_index', $data);
    }


    /**
     * 会员信息查询下载
     * @param Request $request
     * @return mixed
     */
    public function download(Request $request)
    {
        $client = Auth::guard('company')->user();
        $cid = $client->id;
        $uid = intval($request->input('uid'));
        $openid = $request->input('openid');
        $phone = intval($request->input('phone'));
        $realname = $request->input('realname');
        $usertag = $request->input('tag');
        $creditStart = intval($request->input('credit_start'));
        if ($creditStart <= 0) {
            $creditStart = 0;
        };
        $creditEnd = intval($request->input('credit_end'));
        if ($creditEnd < $creditStart ) {
            return back()->with('info', '结束值必须大于开始值');;
        }
        $query = User::where('cid', $cid)->where('is_registered', 1);
        if ($uid) {
            $query = $query->where('id', $uid);
        }
        if ($openid) {
            $wxUser = WxUser::where('cid', $cid)->where('openid', $openid)->first();
            $uid = $wxUser->uid;
            $query = $query->where('id', $uid);
        }
        if ($phone) {
            $query = $query->where('phone', $phone);
        }
        if ($realname) {
            $query = $query->where('realname', $realname);
        }
        if ($usertag) {
            $tag = Tag::where('cid', $cid)->where('name', $usertag)->first();
            $tid = $tag->id;
            $uids = UserTag::where('cid', $cid)->where('tid', $tid)->pluck('uid');
            $query = $query->whereIn('id', $uids);
        }
        if ($creditStart) {
            $query = $query->where('credit', '>=', $creditStart);
        }
        if ($creditEnd) {
            $query = $query->where('credit', '<=', $creditEnd);
        }
        if ($creditStart && $creditEnd) {
            $query = $query->whereBetween('credit', [$creditStart, $creditEnd]);
        }
        $members = $query->get();
        $openids = [];
        $subscribes = [];
        $memberTags = [];
        $cellData = [
            ['用户id', 'openid', '姓名', '手机号', '积分', '标签', '是否关注']
        ];
        foreach ($members as  $member) {
            $userTags = UserTag::where('cid', $cid)->where('uid', $member->id)->get();
            $wxUsers = WxUser::where('cid', $cid)->where('uid', $member->id)->get();
            foreach ($userTags as $userTag) {
                $tag = Tag::where('cid', $cid)->where('id', $userTag->tid)->first();
                $memberTags[$member->id][$member->id][] = $tag->name;
            };
            foreach ($wxUsers as $wxUser) {
                $openids[$member->id]['openid'] = $wxUser->openid;
                $subscribes[$member->id]['subscribe'] = $wxUser->subscribe;
            }
            $cellData[$member->id]['用户id'] = $member->id;
            $cellData[$member->id]['openid'] = $openids[$member->id]['openid'];
            $cellData[$member->id]['姓名'] = $member->realname;
            $cellData[$member->id]['手机号'] = $member->phone;
            $cellData[$member->id]['积分'] = $member->credit;
            if (!isset($memberTags[$member->id]) ) {
                $cellData[$member->id]['标签'] = " ";
            }else {
                foreach ($memberTags[$member->id][$member->id] as $memberTag) {
                    if (!isset($cellData[$member->id]['标签'])) {
                        $cellData[$member->id]['标签'] = $memberTag . ' ';
                    }
                    $cellData[$member->id]['标签'] .=  $memberTag . ' ';
                }
            }
            $cellData[$member->id]['是否关注'] = $subscribes[$member->id]['subscribe']==1 ?'已关注': '未关注';

        }
            $filename = "会员信息".date('YmdHis', time()).mt_rand(10,20);
            return Excel::create(iconv('UTF-8', 'GBK', $filename), function ($excel) use ($cellData) {
                $excel->sheet('information', function ($sheet) use ($cellData) {
                    $sheet->rows($cellData);
                });
            })->export('csv');

        }

    /**
     * 会员积分记录
     * @param Request $request
     * @return string|View
     */
    public function creditRecord(Request $request)
    {
        $client = Auth::guard('company')->user();
        $uid = intval($request->input('uid'));
        if (!$uid) {
            return 'User is not exist!';
        }
        $user = User::where('cid', $client->id)
            ->where('id', $uid)
            ->first();
        if (!$user) {
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
        $creditTypes = CreditType::whereIn('cid', [$client->id, 0])
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
            'realname' => $user->realname,
            'uid' => $uid,
        ];
        return view('admin.wechat.user_credit_record', $data);
    }


}
