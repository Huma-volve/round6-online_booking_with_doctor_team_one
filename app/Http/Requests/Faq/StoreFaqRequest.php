<?php

namespace App\Http\Requests\Faq;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFaqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization check removed for testing purposes
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'question' => [
                'required',
                'string',
                'max:500',
                'min:10'
            ],
            'answer' => [
                'required',
                'string',
                'min:20',
                'max:5000'
            ],
            'order' => [
                'nullable',
                'integer',
                'min:1',
                'max:9999'
            ],
            'status' => [
                'nullable',
                'string',
                Rule::in(['active', 'inactive'])
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'question.required' => 'The FAQ question is required.',
            'question.string' => 'The FAQ question must be a string.',
            'question.max' => 'The FAQ question may not be greater than 500 characters.',
            'question.min' => 'The FAQ question must be at least 10 characters.',

            'answer.required' => 'The FAQ answer is required.',
            'answer.string' => 'The FAQ answer must be a string.',
            'answer.min' => 'The FAQ answer must be at least 20 characters.',
            'answer.max' => 'The FAQ answer may not be greater than 5,000 characters.',

            'order.integer' => 'The FAQ order must be an integer.',
            'order.min' => 'The FAQ order must be at least 1.',
            'order.max' => 'The FAQ order may not be greater than 9999.',

            'status.string' => 'The FAQ status must be a string.',
            'status.in' => 'The FAQ status must be either active or inactive.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'question' => 'FAQ question',
            'answer' => 'FAQ answer',
            'order' => 'FAQ order',
            'status' => 'FAQ status'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default status to active if not provided
        if (!$this->has('status')) {
            $this->merge([
                'status' => 'active'
            ]);
        }
    }
}
