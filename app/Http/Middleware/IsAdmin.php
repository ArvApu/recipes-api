<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws HttpException
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var $user User */
        if(! ($user = $request->user())) {
            throw new HttpException(403, 'Forbidden');
        }

        if(! ($user->isAdmin())) {
            throw new HttpException(403, 'Forbidden');
        }

        return $next($request);
    }
}
