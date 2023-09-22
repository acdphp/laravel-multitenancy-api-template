<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required', 'mimes:jpeg,jpg,png,svg', 'max:10240'],
        ];
    }
}
