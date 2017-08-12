<?php

namespace App\Http\Middleware;

use App\Entities\SignRecord;
use Auth;
use Cache;
use Carbon\Carbon;
use Closure;
use Log;

class DaySignMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        $today          = Carbon::today();
        $sign_cache_key = 'daysign.' . $today->timestamp . '.' . $user->id;

        if (!Cache::has($sign_cache_key)) {
            if (!SignRecord::whereUserId($user->id)->where('created_at', '>=', $today)->exists()) {
                $user->signRecords()->save(new SignRecord());
                Cache::add($sign_cache_key, $user->id, 86400); // 24*60*60
                Log::info('打卡', ['user' => $user]);
            }
        }

        return $next($request);
    }
}
