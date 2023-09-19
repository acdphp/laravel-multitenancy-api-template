<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, callable $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
