<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ValidateContentTypeHeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): SymfonyResponse $next
     */
    public function handle(Request $request, Closure $next, string ...$contentTypes): SymfonyResponse
    {
        if ($request->isMethodSafe()) {
            return $next($request);
        }

        if (\in_array($request->getContent(), [null, false, ''], true)) {
            return $next($request);
        }

        $contentType = $request->getContentTypeFormat();

        if (!\in_array($contentType, $contentTypes, true)) {
            throw new UnsupportedMediaTypeHttpException('Content-Type Header Invalid');
        }

        return $next($request);
    }
}
