<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, авторизован ли пользователь и имеет ли роль 'leader'
        if (!auth()->check() || !auth()->user()->isLeader()) {
            abort(403, 'Доступ только для ведущих мастер-классов');
        }

        return $next($request);
    }
}
