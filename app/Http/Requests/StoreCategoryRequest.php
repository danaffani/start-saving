<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCategoryRequest extends FormRequest
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
            'category_group_id' => 'required|exists:category_groups,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:255',
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
            'category_group_id.required' => 'The category group is required.',
            'category_group_id.exists' => 'The category group does not exist.',
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must be less than 255 characters.',
            'slug.required' => 'The slug is required.',
            'slug.string' => 'The slug must be a string.',
            'slug.max' => 'The slug must be less than 255 characters.',
            'slug.unique' => 'The slug must be unique.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description must be less than 255 characters.',
        ];
    }
}
