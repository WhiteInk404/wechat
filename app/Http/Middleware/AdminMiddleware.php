<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AdminMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('请登录后操作', 401);
            } else {
                return redirect()->guest('/login');
            }
        } else {
            // 非管理员无法使用后台
            if (!$this->auth->user()->hasRole('administrator')) {
                $request->session()->flush();
                $request->session()->regenerate();
                flash('非管理员无法使用后台', 'danger');

                return redirect()->guest('/login');
            }
        }

        return $next($request);
    }
}
