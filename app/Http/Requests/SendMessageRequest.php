<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'body'            => 'required|string|min:1|max:2000',
            'announcement_id' => 'sometimes|required|exists:announcements,id',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Le message ne peut pas être vide.',
            'body.max'      => 'Le message ne peut pas dépasser 2000 caractères.',
        ];
    }
}
