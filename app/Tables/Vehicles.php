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
            ->allowedSorts(['id', 'brand', 'model', 'version', 'engine', 'factory_specification_fuel_usage', 'average_fuel_usage', 'mileage_start', 'mileage_latest', 'purchase_date', 'license_plate', 'fuel_type', 'created_at'])
            ->allowedFilters(['id', 'brand', 'model', 'version', 'engine', 'factory_specification_fuel_usage', 'average_fuel_usage', 'mileage_start', 'mileage_latest', 'purchase_date', 'license_plate', 'fuel_type', 'created_at', $globalSearch]);
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
            ->column('brand', sortable: true)
            ->column('model', sortable: true)
            ->column('version', sortable: true)
            ->column('engine', sortable: true)
            ->column('factory_specification_fuel_usage', sortable: true)
            ->column('average_fuel_usage', sortable: true)
            ->column('mileage_start', sortable: true)
            ->column('mileage_latest', sortable: true)
            ->column('purchase_date', sortable: true)
            ->column('license_plate', sortable: true)
            ->column('fuel_type', sortable: true)
            ->column('action')
            ->paginate(15);
    }
}
