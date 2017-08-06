<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\User;
use Auth;

/**
 * Class UsersAPIController
 *
 * @package App\Http\Controllers\API
 */
class UserAPIController extends AppBaseController
{
    public function show($id)
    {
        $user = User::with(['userinfo', 'wechatUser'])->find($id);

        return $this->sendResponse($user->toArray());
    }

    public function me()
    {
        $userinfo = Auth::user()->load(['userinfo', 'wechatUser']);

        return $this->sendResponse($userinfo->toArray());
    }
}
