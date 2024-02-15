<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refueling extends Model
{
    protected $fillable = ['vehicle_id', 'date', 'gas_station', 'fuel_type', 'amount', 'unit_price', 'total_price', 'mileage_begin', 'mileage_end', 'fuel_usage_onboard_computer', 'fuel_usage', 'costs_per_kilometer', 'tyres', 'climate_control', 'routes', 'driving_style', 'comments'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
