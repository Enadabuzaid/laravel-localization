<?php

declare(strict_types=1);

namespace Enadstack\Localization\Http\Controllers;

use Enadstack\Localization\Http\Requests\UpdateLocaleRequest;
use Enadstack\Localization\Support\Locales;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    /**
     * Remember the visitor's chosen locale and return to the previous page.
     */
    public function __invoke(UpdateLocaleRequest $request, Locales $locales): RedirectResponse
    {
        Cookie::queue($locales->cookieName(), $request->validated('locale'), $locales->cookieMinutes());

        return back();
    }
}
