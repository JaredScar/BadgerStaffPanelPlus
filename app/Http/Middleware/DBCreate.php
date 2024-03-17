<?php 

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\DBCreate as Middleware;

class DBCreate extends Middleware

public function handle($request, Closure $next)
{
    $response = $next($request);
    $response->headers->set('Content-Type', 'text/event-stream');
    $response->headers->set('Cache-Control', 'no-cache');
    $response->headers->set('X-Accel-Buffering', 'no');

    return $response;
}

?>