<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
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
            'brand' => ['required', 'string', 'max:50'], 
            'model' => ['required', 'string', 'max:50'],
            'version' => ['required', 'string', 'max:50'],
            'engine' => ['required', 'string', 'max:50'],
            'factory_specification_fuel_usage' => ['required', 'numeric'],
            'mileage_start' => ['required', 'integer'],
            'purchase_date' => ['required', 'date'],
            'license_plate' => ['nullable', 'string'],
            'fuel_type' => ['required', 'string'],
        ];
    }
}
