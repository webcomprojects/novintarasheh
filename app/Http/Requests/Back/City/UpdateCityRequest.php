<?php

namespace App\Http\Requests\Back\City;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|string',
            'province_id'   => 'required|exists:provinces,id',
            'latitude'      => 'nullable|string',
            'longitude'     => 'nullable|string',
            'is_active'     => 'nullable',
            'ordering'      => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active'   => $this->has('is_active'),
        ]);
    }
}