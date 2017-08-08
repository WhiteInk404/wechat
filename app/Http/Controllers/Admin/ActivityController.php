<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Activity;
use App\Entities\Team;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityCreateRequest;
use App\Http\Requests\ActivityUpdateRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Log;

class ActivityController extends Controller
{
    protected $per_page = 20;

    public function index()
    {
        $activities = Activity::paginate($this->per_page);

        return view('admin.activities.index')->with(['activities' => $activities]);
    }

    public function show($id)
    {
        $activity = Activity::find($id);

        $teams = Team::where('activity_id', $id)->orderBy('count', 'desc')->paginate($this->per_page);

        return view('admin.activities.show')->with(['activity' => $activity, 'teams' => $teams, 'page' => request('page', 1), 'per_page' => $this->per_page]);
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(ActivityCreateRequest $request)
    {
        $inputs = array_filter($request->only(['name', 'description', 'begin_time', 'end_time', 'pic_url']), function ($value) {
            return !is_null($value) && $value != '';
        });

        $inputs['labels'] = implode(',', [$request->get('left_label'), $request->get('right_label')]);
        if (Activity::whereLabels($inputs['labels'])->exists()) {
            return redirect()->back()->withInput()->withErrors(new MessageBag(['labels' => '活动符号规则已存在']));
        }

        try {
            Activity::create($inputs);

            flash('创建活动成功', 'success');

            return redirect()->back();
        } catch (Exception $exception) {
            flash('创建活动出了点问题', 'error');

            Log::error(__METHOD__, ['exception' => $exception->getMessage()]);

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $activity = Activity::find($id);

        return view('admin.activities.edit')->with(['activity' => $activity]);
    }

    public function update(ActivityUpdateRequest $request, $id)
    {
        $inputs = array_filter($request->only(['name', 'description', 'begin_time', 'end_time', 'pic_url']), function ($value) {
            return !is_null($value);
        });
        try {
            $activity = Activity::find($id);
            $activity->update($inputs);
            flash('修改成功', 'success');

            return redirect()->back();
        } catch (Exception $exception) {
            flash('修改失败', 'error');
            Log::info(__METHOD__, ['exception' => $exception->getMessage()]);

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        Activity::whereId($id)->delete();

        return redirect(route('admin.activities.index'));
    }

    public function removePic(Request $request)
    {
        //        $full_path = $request->get('key');
        if ($request->isXmlHttpRequest()) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => '删除成功',
            ]);
        } else {
            return response();
        }
    }

    public function upload(Request $request)
    {
        $file = $request->file('upload');

        $upload_path   = $file->store('activities/images', 'qiniu');
        $response_data = [
            'file_id' => $request->get('file_id'),
            'path'    => $upload_path,
        ];
        if ($request->isXmlHttpRequest()) {
            return response()->json([
                'success' => true,
                'data'    => $response_data,
                'message' => '上传成功',
            ]);
        } else {
            return response($response_data);
        }
    }
}
