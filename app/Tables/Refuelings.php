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
            ->column(key: 'vehicle.brand', label: 'Brand', as: function ($fuel_type) {
                $fuel_type = ucfirst($fuel_type);
        
                $fuel_type = str_replace("_", " ", $fuel_type);
        
                return $fuel_type;
            })
            ->selectFilter(
                key: 'vehicles_id',
                options: $vehicles,
                label: 'Brand'
            )
            ->column(key: 'vehicle.model', label: 'Model')
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
        
                return $fuel_usage_onboard_computer;
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
            ->column('tyres', sortable: true, as: function ($tyres) {
                if ($tyres == "summer_tyres") {
                    $tyres = '<i class="material-symbols-outlined">sunny</i>';
                } else if ($tyres == "winter_tyres") {
                    $tyres = '<i class="material-symbols-outlined">ac_unit</i>';
                } else if ($tyres == "all_season_tyres") {
                    $tyres = '<i class="material-symbols-outlined">sunny_snowing</i>';
                }
            
                return new \Illuminate\Support\HtmlString($tyres);
            })
            ->column('climate_control', sortable: true, as: function ($climate_control) {
                $climate_control = json_decode($climate_control);   
                $iconMap = [
                    'airconditioning' => '<i class="material-symbols-outlined">ac_unit</i>',
                    'heater' => '<i class="material-symbols-outlined">local_fire_department</i>',
                ];
            
                // Display icons based on the array values
                $climate_control = collect($climate_control)->map(function ($control) use ($iconMap) {
                    return $iconMap[$control] ?? '';
                })->implode(' ');
            
                return new \Illuminate\Support\HtmlString($climate_control);
            })
            ->column('routes', sortable: true, as: function ($routes) {
                $routes = json_decode($routes);
                $iconMap = [
                    'city' => '<i class="material-symbols-outlined">location_city</i>',
                    'country_roads' => '<i class="material-symbols-outlined">landscape</i>',
                    'highway' => '<svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 601.00134 601.00159" height="1.5rem" width="1.5rem" xml:space="preserve" version="1.1" id="svg3968"><metadata id="metadata3974"><rdf:RDF><cc:Work rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"/><dc:title/></cc:Work></rdf:RDF></metadata><defs id="defs3972"/><g transform="matrix(1.25,0,0,-1.25,0,601.00159)" id="g3976"><g id="g3978"><path id="path3980" style="fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none" d="M 29.199,480.403 C 0.336,480.418 0.398,451.598 0.398,451.598 l 0,-422.398 c 0,0 -0.062,-28.825 28.801,-28.797 l 422.403,0 C 480.648,0.372 480.398,29.2 480.398,29.2 l 0,422.398 c 0,0 0.239,28.899 -28.796,28.805 l -422.403,0 z"/><path id="path3982" style="fill:rgb(107 114 128 / var(--tw-text-opacity));fill-opacity:1;fill-rule:nonzero;stroke:none" d="M 29.199,470.801 C 18.602,470.801 10,462.208 10,451.602 L 10,29.2 C 10,18.59 18.602,10 29.199,10 l 422.403,0 c 10.601,0 19.199,8.59 19.199,19.2 l 0,422.402 c 0,10.606 -8.598,19.199 -19.199,19.199 l -422.403,0 z"/><path id="path3984" style="fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none" d="M 29.199,480.801 C 14.633,480.809 7.258,473.485 3.617,466.184 -0.023,458.883 0,451.594 0,451.594 L 0,29.2 C 0,29.2 -0.02,21.911 3.617,14.614 7.258,7.313 14.633,-0.011 29.199,0 l 422.403,0 c 14.656,-0.015 22.035,7.313 25.652,14.614 3.613,7.297 3.547,14.582 3.547,14.586 l 0,422.398 c 0,0.004 0.062,7.309 -3.555,14.613 -3.617,7.305 -10.996,14.637 -25.644,14.59 l -422.403,0 z m 0,-0.398 422.403,0 c 29.035,0.094 28.796,-28.805 28.796,-28.805 l 0,-422.398 c 0,0 0.25,-28.828 -28.796,-28.797 l -422.403,0 C 0.336,0.375 0.398,29.2 0.398,29.2 l 0,422.398 c 0,0 -0.062,28.82 28.801,28.805 z"/><path id="path3986" style="fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none" d="m 252.34,305.418 -4.711,122.793 21.672,0 42.515,-122.793 -59.476,0 z m -83.34,0 42.512,122.793 21.676,0 -4.723,-122.793 -59.465,0 z m -76.68,-50.585 0,21.679 -39.722,0 0,14.453 375.609,0 0,-14.453 -39.727,0 0,-21.679 -36.113,0 0,21.679 -223.929,0 0,-21.679 -36.118,0 z m 169.75,-202.247 -7.765,202.247 75.004,0 70.015,-202.247 -137.254,0 z m -180.593,0 70.015,202.247 75.02,0 -7.782,-202.247"/></g></g></svg>',
                ];
            
                $routes = collect($routes)->map(function ($route) use ($iconMap) {
                    return $iconMap[$route] ?? '';
                })->implode(' ');
            
                return new \Illuminate\Support\HtmlString($routes);
            })
            ->column('driving_style', sortable: true, as: function ($driving_style) {
                if ($driving_style == "slow") {
                    $driving_style = '<i class="material-symbols-outlined">signal_cellular_1_bar</i>';
                } else if ($driving_style == "normal") {
                    $driving_style = '<i class="material-symbols-outlined">signal_cellular_3_bar</i>';
                } else if ($driving_style == "fast") {
                    $driving_style = '<i class="material-symbols-outlined">signal_cellular_4_bar</i>';
                }
            
                return new \Illuminate\Support\HtmlString($driving_style);
            })
            ->column('comments', sortable: true)
            ->column('action')
            ->paginate(15);
    }
}
