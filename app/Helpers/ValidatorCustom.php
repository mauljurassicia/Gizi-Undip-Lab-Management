<?php

namespace App\Helpers;

use App\Enums\ResponseCodeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;


class ValidatorCustom extends FormRequest
{
    public function __construct(private Request $requests) {}

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ResponseJson::make(
                ResponseCodeEnum::STATUS_BAD_REQUEST,
                "Bad Request",
                error: $validator->errors()
            )->send()
        );
    }
}
