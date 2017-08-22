<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ParseWordbook;
use Exception;
use Illuminate\Http\Request;
use Log;

class WordbookController extends Controller
{
    protected $per_page = 20;

    public function create()
    {
        return view('admin.wordbook.create');
    }

    public function upload(Request $request)
    {
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
