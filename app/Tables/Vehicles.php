<?php

namespace App\Tables;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;


class Vehicles extends AbstractTable
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
                        ->orWhere('brand', 'LIKE', "%{$value}%")
                        ->orWhere('model', 'LIKE', "%{$value}%")
                        ->orWhere('version', 'LIKE', "%{$value}%")
                        ->orWhere('engine', 'LIKE', "%{$value}%")
                        ->orWhere('license_plate', 'LIKE', "%{$value}%");
                });
            });
        });
        return QueryBuilder::for(Vehicle::class)
            ->where('user_id', $userId)
            ->defaultSort('id')
            ->allowedSorts(['id', 'brand', 'model', 'version', 'engine', 'factory_specification_fuel_usage', 'average_fuel_usage', 'mileage_start', 'mileage_latest', 'purchase_date', 'license_plate', 'fuel_type'])
            ->allowedFilters(['id', 'brand', 'model', 'version', 'engine', 'factory_specification_fuel_usage', 'average_fuel_usage', 'mileage_start', 'mileage_latest', 'purchase_date', 'license_plate', 'fuel_type', $globalSearch]);
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->withGlobalSearch(columns: ['id', 'brand', 'model', 'version', 'engine', 'license_plate'])
            ->column('id', sortable: true)
            ->column('brand', sortable: true, as: function ($brand) {
                $brand = ucfirst($brand);
        
                $brand = str_replace("_", " ", $brand);
        
                return $brand;
            })
            ->column('model', sortable: true)
            ->column('version', sortable: true)
            ->column('engine', sortable: true)
            ->column('factory_specification_fuel_usage', sortable: true, as: function ($factory_specification_fuel_usage) {
                $factory_specification_fuel_usage = str_replace(".", ",", $factory_specification_fuel_usage);
        
                return $factory_specification_fuel_usage;
            })
            ->column('average_fuel_usage', sortable: true, as: function ($average_fuel_usage) {
                if (empty($average_fuel_usage)) {
                    $average_fuel_usage = "-";
                }
                $average_fuel_usage = str_replace(".", ",", $average_fuel_usage);
        
                return $average_fuel_usage;
            })
            ->column('mileage_start', sortable: true)
            ->column('mileage_latest', sortable: true, as: function ($mileage_latest) {
                if (empty($mileage_latest)) {
                    $mileage_latest = "-";
                }
        
                return $mileage_latest;
            })
            ->column('purchase_date', sortable: true, as: function ($purchase_date) {
                $purchase_date = date("d-m-Y", strtotime($purchase_date));
        
                return $purchase_date;
            })
            ->column('license_plate', sortable: true, as: function ($license_plate) {
                if (empty($license_plate)) {
                    $license_plate = "-";
                }
        
                return $license_plate;
            })
            ->column('fuel_type', sortable: true, as: function ($fuel_type) {
                $fuel_type = ucfirst($fuel_type);
        
                $fuel_type = str_replace("_", " ", $fuel_type);
                $fuel_type = str_replace("lpg", "LPG", $fuel_type);
        
                return $fuel_type;
            })
            ->column('action')
            ->paginate(15);
    }
}
