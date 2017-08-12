<?php

namespace App\Http\Controllers\API;

use App\Entities\Wordbook;
use App\Entities\WordbookContent;
use App\Entities\WordRecord;
use App\Http\Controllers\AppBaseController;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;

class WordRecordAPIController extends AppBaseController
{
    public function next(Request $request)
    {
        /** @var User $user */
        $user                = Auth::user();
        $status              = $request->get('status', WordRecord::STATUS_REMEMBER);
        $wordbook_content_id = $request->get('wordbook_content_id');
        $next_wordbook       = $request->get('next_wordbook', 0);

        $wordbook_state = $user->wordbookState;
        $wordbook_id    = $wordbook_state->wordbook_id;
        // 如果一本背完了，要开始背下一本，则获取下一个单词本的信息，并更新该用户单词本记录信息的状态
        if ($next_wordbook == 1) {
            $remembered_book_ids = WordRecord::whereUserId($user->id)->distinct()->select('wordbook_id')->get()->pluck('wordbook_id');
            $wordbook            = Wordbook::whereNotIn('id', $remembered_book_ids)->orderByRaw('`id` asc,`sort` asc')->first();
            // 如果全都背完了。。厉害了。
            if (!$wordbook) {
                return $this->sendError([], 'over');
            }
            $wordbook_state->wordbook_id    = $wordbook->id;
            $wordbook_state->word_total     = $wordbook->contents()->count();
            $wordbook_state->remember_total = 0;
            $wordbook_state->save();
            $wordbook_id = $wordbook_state->wordbook_id;
        } else {
            // 传递了单词信息
            if ($wordbook_content_id) {
                // 并且记得这个单词
                if ($status == WordRecord::STATUS_REMEMBER) {
                    $this_total                     = $wordbook_state->remember_total + 1;
                    $wordbook_state->remember_total = $this_total > $wordbook_state->word_total ? $wordbook_state->remember_total : $this_total;
                    $wordbook_state->save();
                }
                // 增加一条记录
                WordRecord::create([
                    'user_id'             => $user->id,
                    'wordbook_content_id' => $wordbook_content_id,
                    'wordbook_id'         => $wordbook_id,
                    'status'              => $status,
                ]);
            }
        }

        // 判断该单词本是否已经背完
        if ($wordbook_state->remember_total == $wordbook_state->word_total) {
            return $this->sendError([], 'next');
        }

        // 随机取一条今日未背过的数据
        $word = WordbookContent::whereWordbookId($wordbook_id)
            ->orderBy(DB::raw('RAND()'))
            ->whereDoesntHave('wordRecord', function ($sql) {
                return $sql->where('status', WordRecord::STATUS_REMEMBER)->orWhere(function ($sql) {
                    return $sql->where('created_at', '>=', Carbon::today())->where('status', '!=', WordRecord::STATUS_REMEMBER);
                });
            })->first();

        // 如果今日已经没有数据了，则返回背过的但不记得的或者模糊的单词
        if (!$word) {
            $word = WordbookContent::whereWordbookId($wordbook_id)
                ->orderBy(DB::raw('RAND()'))
                ->whereDoesntHave('wordRecord', function ($sql) {
                    return $sql->where('status', WordRecord::STATUS_REMEMBER);
                })->first();
            Log::info('没有数据啦，返回一个背过的', [$word]);
        }

        return $this->sendResponse($word);
    }
}
