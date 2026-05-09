<?php

namespace App\Http\Requests\Consultant;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'experience_years' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'slot_duration' => 'required|integer|in:15,30,45,60,90,120',
        ];
    }
}
