<?php

namespace App\Tables;

use App\Models\Refueling;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;

class Refuelings extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        $userId = Auth::id();

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection :: wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('date', 'LIKE', "%{$value}%")
                        ->orWhere('gas_station', 'LIKE', "%{$value}%")
                        ->orWhere('fuel_type', 'LIKE', "%{$value}%")
                        ->orWhere('amount', 'LIKE', "%{$value}%")
                        ->orWhere('total_price', 'LIKE', "%{$value}%")
                        ->orWhere('mileage_begin', 'LIKE', "%{$value}%")
                        ->orWhere('mileage_end', 'LIKE', "%{$value}%")
                        ->orWhere('fuel_usage_onboard_computer', 'LIKE', "%{$value}%")
                        ->orWhere('fuel_usage', 'LIKE', "%{$value}%")
                        ->orWhere('costs_per_kilometer', 'LIKE', "%{$value}%")
                        ->orWhere('tyres', 'LIKE', "%{$value}%")
                        ->orWhere('climate_control', 'LIKE', "%{$value}%")
                        ->orWhere('routes', 'LIKE', "%{$value}%")
                        ->orWhere('driving_style', 'LIKE', "%{$value}%")
                        ->orWhere('comments', 'LIKE', "%{$value}%");
                });
            });
        });
        return QueryBuilder::for(Refueling::class)
            ->whereHas('vehicle', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->defaultSort('id')
            ->allowedSorts(['id', 'vehicle_id', 'date', 'gas_station', 'fuel_type', 'amount', 'total_price', 'mileage_begin', 'mileage_end', 'fuel_usage_onboard_computer', 'fuel_usage', 'costs_per_kilometer', 'tyres', 'climate_control', 'routes', 'driving_style', 'comments'])
            ->allowedFilters(['id', 'vehicle_id', 'date', 'gas_station', 'fuel_type', 'amount', 'total_price', 'mileage_begin', 'mileage_end', 'fuel_usage_onboard_computer', 'fuel_usage', 'costs_per_kilometer', 'tyres', 'climate_control', 'routes', 'driving_style', 'comments', $globalSearch]);
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $vehicles = Vehicle::all()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                $brand = ucfirst($vehicle->brand),
                'label' => "{$brand} {$vehicle->model} {$vehicle->version} {$vehicle->engine}",
            ];
        })->pluck('label', 'id')->toArray();

        $table
            ->withGlobalSearch(columns: ['id', 'vehicle_id', 'date', 'gas_station', 'fuel_type', 'amount', 'total_price', 'mileage_begin', 'mileage_end', 'fuel_usage_onboard_computer', 'fuel_usage', 'costs_per_kilometer', 'tyres', 'climate_control', 'routes', 'driving_style', 'comments'])
            ->column('id', sortable: true)
            ->column(key: 'vehicles.brand', label: 'Brand')
            ->selectFilter(
                key: 'vehicles_id',
                options: $vehicles,
                label: 'Brand'
            )
            ->column(key: 'vehicles.model', label: 'Model')
            ->column('date', sortable: true, as: function ($date) {
                $date = date("d-m-Y", strtotime($date));
        
                return $date;
            })
            ->column('gas_station', sortable: true)
            ->column('fuel_type', sortable: true, as: function ($fuel_type) {
                $fuel_type = ucfirst($fuel_type);
        
                $fuel_type = str_replace("_", " ", $fuel_type);
                $fuel_type = str_replace("lpg", "LPG", $fuel_type);
                $fuel_type = str_replace("cng", "CNG", $fuel_type);
        
                return $fuel_type;
            })
            ->column('amount', sortable: true, as: function ($amount) {
                $amount = str_replace(".", ",", $amount);
        
                return $amount;
            })
            ->column('unit_price', sortable: true, as: function ($unit_price) {
                $unit_price = str_replace(".", ",", $unit_price);
        
                return $unit_price;
            })
            ->column('total_price', sortable: true, as: function ($total_price) {
                $total_price = str_replace(".", ",", $total_price);
        
                return $total_price;
            })
            ->column('mileage_begin', sortable: true, as: function ($mileage_begin) {
                if (empty($mileage_begin)) {
                    $mileage_begin = "-";
                }
        
                return $mileage_begin;
            })
            ->column('mileage_end', sortable: true)
            ->column('fuel_usage_onboard_computer', sortable: true, as: function ($fuel_usage_onboard_computer) {
                $fuel_usage_onboard_computer = str_replace(".", ",", $fuel_usage_onboard_computer);
        
                return $factory_specification_fuel_usage;
            })
            ->column('fuel_usage', sortable: true, as: function ($fuel_usage) {
                if (empty($fuel_usage)) {
                    $fuel_usage = "-";
                }
                $fuel_usage = str_replace(".", ",", $fuel_usage);
        
                return $fuel_usage;
            })
            ->column('costs_per_kilometer', sortable: true, as: function ($costs_per_kilometer) {
                $costs_per_kilometer = str_replace(".", ",", $costs_per_kilometer);
        
                return $costs_per_kilometer;
            })
            ->column('tyres', sortable: true)
            ->column('climate_control', sortable: true)
            ->column('routes', sortable: true)
            ->column('driving_style', sortable: true)
            ->column('comments', sortable: true)
            ->column('action')
            ->paginate(15);
    }
}
