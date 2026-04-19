<?php

namespace App\Http\Requests;

use App\Models\Announcement;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
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
        return [
            'title.required'       => 'Le titre est obligatoire.',
            'title.min'            => 'Le titre doit contenir au moins 5 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.min'      => 'La description doit contenir au moins 20 caractères.',
            'price.required'       => 'Le prix est obligatoire.',
            'price.numeric'        => 'Le prix doit être un nombre.',
            'condition.required'   => "L'état de l'article est obligatoire.",
            'category_id.required' => 'Veuillez choisir une catégorie.',
            'city_id.required'     => 'Veuillez choisir une ville.',
            'images.*.image'       => 'Les fichiers doivent être des images.',
            'images.*.max'         => 'Chaque image ne doit pas dépasser 4 Mo.',
            'images.max'           => 'Vous pouvez téléverser au maximum 5 images.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_negotiable' => $this->boolean('is_negotiable'),
        ]);
    }
}
