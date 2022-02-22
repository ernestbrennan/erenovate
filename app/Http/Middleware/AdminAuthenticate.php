<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuthenticate
{

    public function handle($request, Closure $next)
    {
        $post = collect($request->all());
        if ($post->has('admin-api-token')) {
            if (in_array($post->get('admin-api-token'), env('API_TOKEN'))) {
                return $next($request);
            }
        }
        if ($request->hasHeader('admin-api-token')) {
            if (!empty($request->header('admin-api-token')) &&  $request->header('admin-api-token') === env('API_TOKEN')) {
                return $next($request);
            }
        }
        return response('Unauthorized.', 401);
    }
}
