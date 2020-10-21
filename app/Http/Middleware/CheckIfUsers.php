<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

class CheckIfUsers
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
        if(! ($userId = $request->route('userId'))) {
            return $next($request);
        }

        if((int)$userId === $request->user()->id) {
            return $next($request);
        }

        if($request->user()->isAdmin()) {
            return $next($request);
        }

        throw new HttpException(403, 'Forbidden');
    }
}
