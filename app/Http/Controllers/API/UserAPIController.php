<?php

namespace App\Http\Controllers\API;

use App\Entities\Wordbook;
use App\Entities\WordbookState;
use App\Http\Controllers\AppBaseController;
use Auth;

class UserAPIController extends AppBaseController
{
    public function me()
    {
        $user = Auth::user()->append(['sign_count']);

        // 如果还没有背单词信息，预创建一份
        if (!$user->wordbookState) {
            $wordbook       = Wordbook::orderByRaw('id asc,sort asc')->first();
            $wordbook_state = new WordbookState([
                'wordbook_id'               => $wordbook->id,
                'word_total'                => $wordbook->contents()->count(),
                'remember_total'            => 0,
                'remembered_wordbook_total' => 0,
            ]);
            $user->wordbookState()->save($wordbook_state);
        }

        return $this->sendResponse($user->load(['wordbookState', 'reminder'])->toArray());
    }
}
