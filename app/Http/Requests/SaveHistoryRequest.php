<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return true;
        return $this->user() !== null; // only logged-in users can save history
    }

    public function rules(): array
    {
        return [
            'search_term' => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'search_lat'  => 'nullable|numeric|between:-90,90',
            'search_long' => 'nullable|numeric|between:-180,180',
        ];
    }
}
