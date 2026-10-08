<?php

declare(strict_types=1);

namespace Enadstack\Localization\Http\Requests;

use Enadstack\Localization\Support\Locales;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocaleRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(Locales $locales): array
    {
        return [
            'locale' => ['required', 'string', Rule::in($locales->codes())],
        ];
    }
}
