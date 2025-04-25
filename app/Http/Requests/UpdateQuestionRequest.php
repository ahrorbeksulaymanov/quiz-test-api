<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5200', // ~5MB max
            'order' => 'nullable|integer',
            'level_id' => 'nullable|exists:levels,id',
            'category_id' => 'nullable|exists:categories,id',
            'correct_answer_id' => 'nullable|integer',
            'ball' => 'required|integer',
        ];
    }
}
