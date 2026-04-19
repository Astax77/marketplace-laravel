<?php

namespace App\Http\Requests;

use App\Models\Announcement;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('announcement'));
    }

    public function rules(): array
    {
        return [
            'title'         => 'required|string|min:5|max:150',
            'description'   => 'required|string|min:20|max:5000',
            'price'         => 'required|numeric|min:0|max:99999999',
            'is_negotiable' => 'boolean',
            'condition'     => 'required|in:' . implode(',', Announcement::CONDITIONS),
            'category_id'   => 'required|exists:categories,id',
            'city_id'       => 'required|exists:cities,id',
            'images'        => 'nullable|array|max:5',
            'images.*'      => 'image|mimes:jpeg,png,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return (new StoreAnnouncementRequest())->messages();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_negotiable' => $this->boolean('is_negotiable'),
        ]);
    }
}
