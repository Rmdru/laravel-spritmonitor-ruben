<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
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
            'user_id' => ['nullable', 'integer', 'max:20'],
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'version' => ['required', 'string', 'max:50'],
            'engine' => ['required', 'string', 'max:50'],
            'factory_specification_fuel_usage' => ['required', 'numeric'],
            'average_fuel_usage' => ['nullable', 'numeric'],
            'mileage_start' => ['required', 'integer'],
            'mileage_latest' => ['nullable', 'integer'],
            'purchase_date' => ['required', 'date'],
            'license_plate' => ['nullable', 'string'],
            'fuel_type' => ['required', 'string'],
        ];
    }
}
