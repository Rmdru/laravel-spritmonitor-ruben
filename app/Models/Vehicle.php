<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = ['user_id', 'brand', 'model', 'version', 'engine', 'factory_specification_fuel_usage', 'average_fuel_usage', 'mileage_start', 'mileage_latest', 'purchase_date', 'license_plate', 'fuel_type'];
}
