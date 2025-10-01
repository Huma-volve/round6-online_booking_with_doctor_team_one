<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DoctorFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // If you want only authenticated users:
        return Auth::check();

        // return true; // allow anyone
    }

    public function rules(): array
    {
        return [
            'name'         => 'nullable|string|max:255',
            'specialist'   => 'nullable|string|max:255',
            'location'     => 'nullable|string|max:255',
            'min_rating'   => 'nullable|numeric|min:0|max:5',
            'max_rating'   => 'nullable|numeric|min:0|max:5',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'radius'       => 'nullable|numeric|min:0.1|max:1000',
            // 'availability' => 'nullable|string', 
            'sort_by'      => 'nullable|in:name,rating,experience,price',
            'sort_order'   => 'nullable|in:asc,desc',
            'page'         => 'nullable|integer|min:1',
            'per_page'     => 'nullable|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            // If one coordinate param is given, all must be present
            if (
                (isset($data['latitude']) || isset($data['longitude'])) &&
                !(isset($data['latitude']) && isset($data['longitude']))
            ) {
                $validator->errors()->add(
                    'location_coordinates',
                    'latitude, longitude, and radius are all required for map-based search.'
                );
            }
        });
    }
}
