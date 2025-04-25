<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable',
            'ball' => 'required',
            'duration' => 'required',
            'age_category_id' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5200', // ~5MB max
        ];
    }
}
