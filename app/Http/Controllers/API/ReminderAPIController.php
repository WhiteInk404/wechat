<?php

namespace App\Http\Controllers\API;

use App\Entities\Reminder;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\ReminderStoreRequest;
use App\User;
use Auth;

class ReminderAPIController extends AppBaseController
{
    public function store(ReminderStoreRequest $request)
    {
        $user = Auth::user();
        $time = $request->get('time');

        if (!strtotime($time)) {
            return $this->sendError(['time' => $time], '传入的时间格式有误');
        }

        if ($user->reminder) {
            $user->reminder()->update(['time' => $time]);
        } else {
            $user->reminder()->save(new Reminder(['time' => $time]));
        }

        return $this->sendResponse(['time' => $time], '设置提醒成功');
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->reminder->delete();

        return $this->sendResponse([], '取消提醒成功');
    }
}
