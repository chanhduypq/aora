<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CheckHeader
{
    public function handle(Request $request, Closure $next)
    {
        if(!$request->hasHeader('http-app-key')) {
            return response()->json(['status' => 'error', 'msg' => 'Bad request!'], Response::HTTP_BAD_REQUEST);
        }
        if($request->header('http-app-key') !== env('APP_SECRET_KEY', null)) {
            return response()->json(['status' => 'error', 'msg' => 'Invalid HTTP_APP_KEY!'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
