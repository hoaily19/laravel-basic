<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle(Request $request, Closure $next): Response
    {
            if (!Auth::check()){
                return redirect('/login');
            }
            $user = Auth::user();
            if ($user->role != 'admin'){
                abort(404);
            }
        return $next($request);
    }
    
}
