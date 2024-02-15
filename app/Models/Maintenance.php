<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = ['vehicle_id', 'date', 'garage', 'type_maintenance', 'apk', 'apk_date', 'washed', 'tyre_pressure', 'tasks_messages', 'total_price', 'mileage_begin', 'mileage_end'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
