<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMaintenanceRequest extends FormRequest
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
            'vehicle_id' => ['required', 'integer', 'max:20'],
            'date' => ['required', 'date'],
            'garage' => ['nullable', 'string', 'max:256'],
            'type_maintenance' => ['nullable', 'string', 'max:100'],
            'apk' => ['nullable', 'array', 'max:100'],
            'apk_date' => ['nullable', 'date', 'max:100'],
            'washed' => ['nullable', 'array', 'max:50'],
            'tyre_pressure' => ['nullable', 'array', 'max:50'],
            'tasks_messages' => ['nullable', 'string'],
            'total_price' => ['nullable', 'numeric'],
            'mileage_begin' => ['nullable', 'integer'],
            'mileage_end' => ['nullable', 'integer'],
        ];
    }
}
