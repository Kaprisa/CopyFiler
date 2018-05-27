<?php

namespace App\Http\Middleware;

use App\History;
use Closure;
use Illuminate\Support\Facades\Auth;

class HistoryRecordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        History::create([
            'user_id' => Auth::guard('api')->id(),
            'action' => $request->action,
            'status' => $response->status
        ]);
        return $response;
    }
}
