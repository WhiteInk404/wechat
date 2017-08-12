<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Auth;

class UserAPIController extends AppBaseController
{
    public function me()
    {
        $userinfo = Auth::user()->load(['wordbookState', 'reminder'])->append(['sign_count']);

        return $this->sendResponse($userinfo->toArray());
    }
}
