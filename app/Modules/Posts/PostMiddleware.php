<?php

namespace App\Modules\Posts;

use App\Core\Auth;
use App\Core\Response;

class PostMiddleware
{
    public function handle($request, $next)
    {
        if (!Auth::check()) {
            Response::json(['error' => 'Unauthorized'], 401);
            exit;
        }

        return $next($request);
    }
}