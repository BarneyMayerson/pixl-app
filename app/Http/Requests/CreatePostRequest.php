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
            'content' => ['required', 'string', 'min:2', 'max:250'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.min' => 'Post content must be at least 2 characters.',
            'content.max' => 'Post content must not exceed 250 characters.',
        ];
    }
}
