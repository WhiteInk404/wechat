<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Wordbook;
use App\Entities\WordbookContent;
use App\Http\Controllers\Controller;
use App\Jobs\ParseWordbook;
use Exception;
use Illuminate\Http\Request;
use Log;

class WordbookController extends Controller
{
    protected $per_page = 100;

    public function index()
    {
        $wordbooks = Wordbook::orderByRaw('`sort` asc,`id` asc')->paginate($this->per_page);

        return view('admin.wordbook.index')->with(['wordbooks' => $wordbooks]);
    }

    public function contents($id)
    {
        $contents = WordbookContent::whereWordbookId($id)->paginate($this->per_page);

        return view('admin.wordbook.contents')->with(['contents' => $contents]);
    }

    public function sort(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            ['sort' => 'required|integer']
        );

        if ($validator->fails()) {
            flash('请输入数字', 'error');

            return redirect()->back();
        }
        $wordbook       = Wordbook::find($id);
        $wordbook->sort = $request->get('sort');
        $wordbook->save();
        flash('修改排序成功', 'success');

        return redirect()->back();
    }

    public function create()
    {
        return view('admin.wordbook.create');
    }

    public function destroy($id)
    {
        Wordbook::whereId($id)->delete();
        flash('删除成功', 'success');

        return redirect()->back();
    }

    public function upload(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            ['upload' => 'required|file']
        );

        if ($validator->fails()) {
            flash('请选择单词本', 'error');

            return redirect()->back();
        }
        $file = $request->file('upload');

        try {
            $wordbook_name = $file->getClientOriginalName();
            $upload_path   = $file->store('wordbook_path');
            flash('上传成功，服务器可能需要花点时间来处理它，请稍候。', 'success');

            $this->dispatch(new ParseWordbook($wordbook_name, $upload_path));

            return redirect()->back();
        } catch (Exception $exception) {
            Log::info('上传单词本异常', ['exception' => $exception]);

            flash('上传可能出了点问题', 'error');

            return redirect()->back();
        }
    }
}
