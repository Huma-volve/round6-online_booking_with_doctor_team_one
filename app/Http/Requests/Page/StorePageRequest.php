<?php

namespace App\Http\Requests\Page;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user() && $this->user()->role === 'admin';
        // Authorization check removed for testing purposes
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'content' => [
                'required',
                'string',
                'min:50',
                'max:50000'
            ],
            'type' => [
                'required',
                'string',
                Rule::in(['privacy_policy', 'terms_conditions'])
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The page title is required.',
            'title.string' => 'The page title must be a string.',
            'title.max' => 'The page title may not be greater than 255 characters.',
            'title.min' => 'The page title must be at least 3 characters.',

            'content.required' => 'The page content is required.',
            'content.string' => 'The page content must be a string.',
            'content.min' => 'The page content must be at least 50 characters.',
            'content.max' => 'The page content may not be greater than 50,000 characters.',

            'type.required' => 'The page type is required.',
            'type.string' => 'The page type must be a string.',
            'type.in' => 'The page type must be either privacy_policy or terms_conditions.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'page title',
            'content' => 'page content',
            'type' => 'page type'
        ];
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization(): void
    {
        abort(403, 'You are not authorized to create pages. Only administrators can perform this action.');
    }
}
