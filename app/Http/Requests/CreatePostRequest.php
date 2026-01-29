<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:250'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.max' => 'Post content must not exceed 250 characters.',
        ];
    }
}
