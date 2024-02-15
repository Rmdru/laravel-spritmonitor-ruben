<?php

namespace App\Tables;

use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Maintenances extends AbstractTable
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
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('date', 'LIKE', "%{$value}%")
                        ->orWhere('garage', 'LIKE', "%{$value}%")
                        ->orWhere('type_maintenance', 'LIKE', "%{$value}%")
                        ->orWhere('apk', 'LIKE', "%{$value}%")
                        ->orWhere('apk_date', 'LIKE', "%{$value}%")
                        ->orWhere('washed', 'LIKE', "%{$value}%")
                        ->orWhere('tyre_pressure', 'LIKE', "%{$value}%")
                        ->orWhere('tasks_messages', 'LIKE', "%{$value}%")
                        ->orWhere('total_price', 'LIKE', "%{$value}%")
                        ->orWhere('mileage_begin', 'LIKE', "%{$value}%")
                        ->orWhere('mileage_end', 'LIKE', "%{$value}%");
                });
            });
        });

        return QueryBuilder::for(Maintenance::class)
            ->whereHas('vehicle', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->defaultSort('id')
            ->allowedSorts(['id', 'vehicle_id', 'date', 'garage', 'type_maintenance', 'washed', 'tyre_pressure', 'apk', 'apk_date', 'tasks_messages', 'total_price', 'mileage_begin', 'mileage_end'])
            ->allowedFilters(['id', 'vehicle_id', 'date', 'garage', 'type_maintenance', 'washed', 'tyre_pressure', 'apk', 'apk_date', 'tasks_messages', 'total_price', 'mileage_begin', 'mileage_end', $globalSearch]);
    }

    /**
     * Configure the given SpladeTable.
     *
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
            ->withGlobalSearch(columns: ['id', 'vehicle_id', 'date', 'garage', 'type_maintenance', 'washed', 'tyre_pressure', 'apk', 'apk_date', 'tasks_messages', 'total_price', 'mileage_begin', 'mileage_end'])
            ->column('id', sortable: true)
            ->column(key: 'vehicle.brand', label: 'Brand', as: function ($fuel_type) {
                $fuel_type = ucfirst($fuel_type);

                $fuel_type = str_replace('_', ' ', $fuel_type);

                return $fuel_type;
            })
            ->column(key: 'vehicle.model', label: 'Model')
            ->column('date', sortable: true, as: function ($date) {
                $date = date('d-m-Y', strtotime($date));

                return $date;
            })
            ->column('garage', sortable: true, as: function ($garage) {
                if (empty($garage)) {
                    $garage = '-';
                }

                return $garage;
            })
            ->column('type_maintenance', sortable: true, as: function ($type_maintenance) {
                $iconMap = [
                    'no_maintenance' => '<span class="material-symbols-outlined">no_crash</span>&nbsp;No',
                    'maintenance' => '<span class="material-symbols-outlined">checklist</span>&nbsp;Maintenance',
                    'small_maintenance' => '<span class="material-symbols-outlined">build</span>&nbsp;Small',
                    'big_maintenance' => '<span class="material-symbols-outlined">construction</span>&nbsp;Big',
                    'longlife_maintenance' => '<span class="material-symbols-outlined">oil_barrel</span>&nbsp;Longlife',
                ];

                $type_maintenance = collect($type_maintenance)->map(function ($maintenance) use ($iconMap) {
                    return $iconMap[$maintenance] ?? '';
                })->implode(' ');

                if (empty($type_maintenance)) {
                    $type_maintenance = '-';
                }

                return new \Illuminate\Support\HtmlString($type_maintenance);
            })
            ->column('apk', sortable: true, as: function ($apk) {
                $apk = json_decode($apk);
                $iconMap = [
                    'no_apk' => '<span class="material-symbols-outlined">no_crash</span>&nbsp;No',
                    'apk' => '<span class="material-symbols-outlined">health_and_safety</span>&nbsp;APK',
                ];

                $apk = collect($apk)->map(function ($apk_icon) use ($iconMap) {
                    return $iconMap[$apk_icon] ?? '';
                })->implode(' ');

                if (empty($apk)) {
                    $apk = '-';
                }

                return new \Illuminate\Support\HtmlString($apk);
            })
            ->column('apk_date', sortable: true, as: function ($apk_date) {
                $apk_date = date('d-m-Y', strtotime($apk_date));

                if ($apk_date == '01-01-1970') {
                    $apk_date = '-';
                }

                return $apk_date;
            })
            ->column('washed', sortable: true, as: function ($washed) {
                $washed = json_decode($washed);
                $iconMap = [
                    'washed' => '<span class="material-symbols-outlined">local_car_wash</span>',
                ];

                $washed = collect($washed)->map(function ($wash) use ($iconMap) {
                    return $iconMap[$wash] ?? '';
                })->implode(' ');

                if (empty($washed)) {
                    $washed = '-';
                }

                return new \Illuminate\Support\HtmlString($washed);
            })
            ->column('tyre_pressure', sortable: true, as: function ($tyre_pressure) {
                $tyre_pressure = json_decode($tyre_pressure);
                $iconMap = [
                    'tyre_pressure' => '<span class="material-symbols-outlined">tire_repair</span>',
                ];

                $tyre_pressure = collect($tyre_pressure)->map(function ($tyre) use ($iconMap) {
                    return $iconMap[$tyre] ?? '';
                })->implode(' ');

                if (empty($tyre_pressure)) {
                    $tyre_pressure = '-';
                }

                return new \Illuminate\Support\HtmlString($tyre_pressure);
            })
            ->column('tasks_messages', sortable: true, as: function ($tasks_messages) {
                if (empty($tasks_messages)) {
                    $tasks_messages = '-';
                }

                return $tasks_messages;
            })
            ->column('total_price', sortable: true, as: function ($total_price) {
                $total_price = str_replace('.', ',', $total_price);

                return $total_price;
            })
            ->column('mileage_begin', sortable: true, as: function ($mileage_begin) {
                if (empty($mileage_begin)) {
                    $mileage_begin = '-';
                }

                return $mileage_begin;
            })
            ->column('mileage_end', sortable: true, as: function ($mileage_end) {
                if (empty($mileage_end)) {
                    $mileage_end = '-';
                }

                return $mileage_end;
            })
            ->column('action')
            ->selectFilter(
                key: 'vehicles_id',
                options: $vehicles,
                label: 'Brand'
            )
            ->paginate(15);
    }
}
