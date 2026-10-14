<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanonicalHost
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $origin = rtrim((string) config('app.url'), '/');
        $canonicalHost = parse_url($origin, PHP_URL_HOST);

        if (
            config('app.env') === 'production'
            && in_array($request->method(), ['GET', 'HEAD'], true)
            && ! $request->is('up')
            && is_string($canonicalHost)
            && $canonicalHost !== ''
            && strtolower($request->getHost()) !== strtolower($canonicalHost)
        ) {
            return redirect()->away($origin.$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
