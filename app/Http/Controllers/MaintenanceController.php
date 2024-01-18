<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Maintenance;
use App\Tables\Maintenances;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Checkboxes;
use ProtoneMedia\Splade\FormBuilder\Radios;
use ProtoneMedia\Splade\FormBuilder\Text;
use App\Http\Requests\CreateMaintenanceRequest;
use ProtoneMedia\Splade\Facades\Toast;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('account.maintenances.index', [
            'maintenances' => Maintenances::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                $brand = ucfirst($vehicle->brand),
                'label' => "{$brand} {$vehicle->model} {$vehicle->version} {$vehicle->engine}",
            ];
        })->pluck('label', 'id')->toArray();

        $form = SpladeForm::make()
        ->action(route('account.maintenance.store'))
        ->class('space-y-4 p-4 bg-white rounded')
        ->fields([
            Select::make('vehicle_id')->label('Vehicle')->options($vehicles)->class("pb-4")->rules(['required']),
            Date::make('date')->label('Date')->rules(['required']),
            Input::make('garage')->label('Garage'),
            Radios::make('type_maintenance')->label('Type maintenance')->options(['no_maintenance' => 'No maintenance', 'maintenance' => 'Maintenance', 'small_maintenance' => 'Small maintenance', 'big_maintenance' => 'Big maintenance', 'longlife_maintenance' => 'Longlife maintenance'])->inline()->rules(['required']),
            Checkboxes::make('apk')->label('APK')->options(['apk' => 'APK']),
            Date::make('apk_date')->label('APK date'),
            Checkboxes::make('washed')->label('Washed')->options(['washed' => 'Washed']),
            Checkboxes::make('tyre_pressure')->label('Tyre pressure')->options(['tyre_pressure' => 'Tyre pressure']),
            Text::make('tasks_messages')->label('Tasks/messages'),
            Input::make('total_price')->label('Total price'),
            Input::make('mileage_begin')->label('Mileage begin'),
            Input::make('mileage_end')->label('Mileage end')->class('pb-4'),
            Submit::make()->label('Save'),
        ]);

        return view('account.maintenances.create', [
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMaintenanceRequest $request)
    {
        $data = $request->validated();

        $data['washed'] = isset($data['washed']) && is_array($data['washed']) ? json_encode($data['washed']) : json_encode([]);
        $data['tyre_pressure'] = isset($data['tyre_pressure']) && is_array($data['tyre_pressure']) ? json_encode($data['tyre_pressure']) : json_encode([]);
        $data['apk'] = isset($data['apk']) && is_array($data['apk']) ? json_encode($data['apk']) : json_encode([]);

        Maintenance::create($data);

        Toast::title('Maintenance created')->autoDismiss(3);

        return to_route('account.maintenance.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        $maintenance->washed = json_decode($maintenance->washed);
        $maintenance->tyre_pressure = json_decode($maintenance->tyre_pressure);
        $maintenance->apk = json_decode($maintenance->apk);

        $vehicles = Vehicle::all()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                $brand = ucfirst($vehicle->brand),
                'label' => "{$brand} {$vehicle->model} {$vehicle->version} {$vehicle->engine}",
            ];
        })->pluck('label', 'id')->toArray();

        $form = SpladeForm::make()
        ->action(route('account.maintenance.update', $maintenance))
        ->method('PUT')
        ->class('space-y-4 p-4 bg-white rounded')
        ->fields([
            Select::make('vehicle_id')->label('Vehicle')->options($vehicles)->class("pb-4")->rules(['required']),
            Date::make('date')->label('Date')->rules(['required']),
            Input::make('garage')->label('Garage'),
            Radios::make('type_maintenance')->label('Type maintenance')->options(['no_maintenance' => 'No maintenance', 'maintenance' => 'Maintenance', 'small_maintenance' => 'Small maintenance', 'big_maintenance' => 'Big maintenance', 'longlife_maintenance' => 'Longlife maintenance'])->inline()->rules(['required']),
            Checkboxes::make('apk')->label('APK')->options(['apk' => 'APK']),
            Date::make('apk_date')->label('APK date'),
            Checkboxes::make('washed')->label('Washed')->options(['washed' => 'Washed']),
            Checkboxes::make('tyre_pressure')->label('Tyre pressure')->options(['tyre_pressure' => 'Tyre pressure']),
            Text::make('tasks_messages')->label('Tasks/messages'),
            Input::make('total_price')->label('Total price'),
            Input::make('mileage_begin')->label('Mileage begin'),
            Input::make('mileage_end')->label('Mileage end')->class('pb-4'),
            Submit::make()->label('Edit'),
        ])
        ->fill($maintenance);

        return view('account.maintenances.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMaintenanceRequest $request, Maintenance $maintenance)
    {
        $data = $request->validated();

        $data['washed'] = isset($data['washed']) && is_array($data['washed']) ? json_encode($data['washed']) : json_encode([]);
        $data['tyre_pressure'] = isset($data['tyre_pressure']) && is_array($data['tyre_pressure']) ? json_encode($data['tyre_pressure']) : json_encode([]);
        $data['apk'] = isset($data['apk']) && is_array($data['apk']) ? json_encode($data['apk']) : json_encode([]);

        $maintenance->update($data);

        Toast::title('Maintenance edited')->autoDismiss(3);

        return to_route('account.maintenance.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        Toast::title('Maintenance deleted')->autoDismiss(3);

        return back();
    }
}
