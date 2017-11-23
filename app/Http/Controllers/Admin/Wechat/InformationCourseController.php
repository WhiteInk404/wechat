<?php

namespace App\Http\Controllers\Admin\Wechat;

use Illuminate\Http\Request;

use App\Models\WxUser;
use App\Models\Tag;
use App\Models\UserTag;
use App\Models\CreditType;
use App\Models\CreditRecord;
use App\Models\InformationCourse;
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
class InformationCourseController extends BaseController
{
    /**
     * 专家资讯首页
     * @return View
     */
    public function index()
    {
        $informationCourses = InformationCourse::paginate(15);
        return view('admin.wechat.information_course_index', compact('informationCourses'));
    }


    /**
     * 修改
     * @param $id 要修改的id
     * @return mixed|View
     */
    public function edit(Request $request, $id)
    {
        $id = intval($id);
        if ($id == 0) {
            $action = 'add';
            return view('admin.wechat.information_course_edit', compact('action', 'id'));
        } else {
            $action = 'edit';
            $informationCourse = InformationCourse::findOrFail($id);
            return view('admin.wechat.information_course_edit', compact('action', 'informationCourse', 'id'));
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
        $name = $request->input('name');
        $title = $request->input('title');
        $status = $request->input('status');
        $image = asset($request->input('image'));
        $introduce = $request->input('introduce');
        $videoUrl = $request->input('video_url');
        $playNums = $request->input('play_nums');
        if ($id == 0) {
            $informationCourse = new InformationCourse();
        } else {
            $informationCourse = InformationCourse::findOrFail($id);
        }
        $informationCourse->name = $name;
        $informationCourse->title = $title;
        $informationCourse->status = $status;
        $informationCourse->image = $image;
        $informationCourse->introduce = $introduce;
        $informationCourse->video_url = $videoUrl;
        $informationCourse->play_nums = $playNums;
        $informationCourse->save();
        if ($id == 0) {
            return $this->ajaxMessage('添加成功');
        } else {
            return $this->ajaxMessage('编辑成功');
        }
    }

    public function destroy($id)
    {
        if (!$id) {
            return $this->ajaxError('未找到该条信息');
        }
        $id = intval($id);
        $informationCourse = InformationCourse::findOrFail($id);
        $result = $informationCourse->delete();
        if ($result) {
            return $this->ajaxMessage('删除成功');
        } else {
            return $this->ajaxError('删除失败');
        }
    }

}
