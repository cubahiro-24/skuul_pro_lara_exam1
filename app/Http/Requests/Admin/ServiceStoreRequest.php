<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role?->nom === 'Admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icone' => ['nullable', 'string', 'max:255'],
            'type_services' => ['nullable', 'array'],
            'type_services.*.nom' => ['required_with:type_services|string|max:255'],
            'type_services.*.description' => ['nullable', 'string'],
            'type_services.*.prix' => ['nullable', 'numeric'],
            'type_services.*.duree_minutes' => ['nullable', 'integer'],
        ];
    }
}
