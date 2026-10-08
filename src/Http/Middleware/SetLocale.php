<?php

declare(strict_types=1);

namespace Enadstack\Localization\Http\Middleware;

use Closure;
use Enadstack\Localization\Support\Locales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(private readonly Locales $locales) {}

    /**
     * Apply the visitor's chosen locale (from the unencrypted locale cookie).
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie($this->locales->cookieName());

        if ($this->locales->isSupported($locale)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
