<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSavingTargetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'The category field is required.',
            'category_id.exists' => 'The selected category is invalid.',
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field must be less than 255 characters.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount field must be a number.',
            'amount.min' => 'The amount field must be at least 0.',
            'description.string' => 'The description field must be a string.',
            'description.max' => 'The description field must be less than 255 characters.',
            'start_date.required' => 'The start date field is required.',
            'start_date.date' => 'The start date field must be a date.',
            'end_date.date' => 'The end date field must be a date.',
            'end_date.after' => 'The end date field must be after the start date.',
        ];
    }
}
