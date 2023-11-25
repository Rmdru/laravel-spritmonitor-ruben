<?php

namespace App\Http\Controllers;

use App\Tables\Vehicles;
use App\Models\Vehicle;
use App\Http\Controllers\Splade;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\Facades\Toast;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Select;


class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('account.vehicles.index', [
            'vehicles' => Vehicles::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = [
            'abarth' => 'Abarth',
            'alfa_romeo' => 'Alfa Romeo',
            'aston_martin' => 'Aston Martin',
            'audi' => 'Audi',
            'bentley' => 'Bentley',
            'bmw' => 'BMW',
            'bugatti' => 'Bugatti',
            'cadillac' => 'Cadillac',
            'chevrolet' => 'Chevrolet',
            'chrysler' => 'Chrysler',
            'citroen' => 'Citroën',
            'dacia' => 'Dacia',
            'daewoo' => 'Daewoo',
            'daihatsu' => 'Daihatsu',
            'dodge' => 'Dodge',
            'donkervoort' => 'Donkervoort',
            'ds' => 'DS',
            'ferrari' => 'Ferrari',
            'fiat' => 'Fiat',
            'fisker' => 'Fisker',
            'ford' => 'Ford',
            'honda' => 'Honda',
            'hummer' => 'Hummer',
            'hyundai' => 'Hyundai',
            'infiniti' => 'Infiniti',
            'iveco' => 'Iveco',
            'jaguar' => 'Jaguar',
            'jeep' => 'Jeep',
            'kia' => 'Kia',
            'ktm' => 'KTM',
            'lada' => 'Lada',
            'lamborghini' => 'Lamborghini',
            'lancia' => 'Lancia',
            'land_rover' => 'Land Rover',
            'landwind' => 'Landwind',
            'lexus' => 'Lexus',
            'lotus' => 'Lotus',
            'maserati' => 'Maserati',
            'maybach' => 'Maybach',
            'mazda' => 'Mazda',
            'mclaren' => 'McLaren',
            'mercedes_benz' => 'Mercedes-Benz',
            'mg' => 'MG',
            'mini' => 'Mini',
            'mitsubishi' => 'Mitsubishi',
            'morgan' => 'Morgan',
            'nissan' => 'Nissan',
            'opel' => 'Opel',
            'peugeot' => 'Peugeot',
            'porsche' => 'Porsche',
            'renault' => 'Renault',
            'rolls_royce' => 'Rolls-Royce',
            'rover' => 'Rover',
            'saab' => 'Saab',
            'seat' => 'Seat',
            'skoda' => 'Skoda',
            'smart' => 'Smart',
            'ssangyong' => 'SsangYong',
            'subaru' => 'Subaru',
            'suzuki' => 'Suzuki',
            'tesla' => 'Tesla',
            'toyota' => 'Toyota',
            'volkswagen' => 'Volkswagen',
            'volvo' => 'Volvo',
        ];

        $fuel_types = [
            'gasoline' => 'Gasoline',
            'gasoline_lpg' => 'Gasoline LPG',
            'gasoline_mild_hybrid' => 'Gasoline mild hybrid',
            'gasoline_full_hybrid' => 'Gasoline full hybrid',
            'gasoline_plugin_hybrid' => 'Gasoline plugin hybrid',
            'gasoline_lpg_mild_hybrid' => 'Gasoline LPG mild hybrid',
            'gasoline_lpg_full_hybrid' => 'Gasoline LPG full hybrid',
            'gasoline_lpg_plugin_hybrid' => 'Gasoline LPG plugin hybrid',
            'diesel' => 'Diesel',
            'diesel_lpg' => 'Diesel LPG',
            'diesel_mild_hybrid' => 'Diesel mild hybrid',
            'diesel_full_hybrid' => 'Diesel full hybrid',
            'diesel_plugin_hybrid' => 'Diesel plugin hybrid',
            'cng' => 'CNG',
            'electricity' => 'Electricity',
            'hydrogen' => 'Hydrogen',
        ];

        return view('account.vehicles.create', [
            'brands' => $brands,
            'fuel_types' => $fuel_types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        $user = Auth::user();

        $validatedData = $request->validated();

        $validatedData['user_id'] = $user->id;

        Vehicle::create($validatedData);

        Toast::title('Vehicle added')->autoDismiss(3);

        return to_route('account.vehicles.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $brands = [
            'abarth' => 'Abarth',
            'alfa_romeo' => 'Alfa Romeo',
            'aston_martin' => 'Aston Martin',
            'audi' => 'Audi',
            'bentley' => 'Bentley',
            'bmw' => 'BMW',
            'bugatti' => 'Bugatti',
            'cadillac' => 'Cadillac',
            'chevrolet' => 'Chevrolet',
            'chrysler' => 'Chrysler',
            'citroen' => 'Citroën',
            'dacia' => 'Dacia',
            'daewoo' => 'Daewoo',
            'daihatsu' => 'Daihatsu',
            'dodge' => 'Dodge',
            'donkervoort' => 'Donkervoort',
            'ds' => 'DS',
            'ferrari' => 'Ferrari',
            'fiat' => 'Fiat',
            'fisker' => 'Fisker',
            'ford' => 'Ford',
            'honda' => 'Honda',
            'hummer' => 'Hummer',
            'hyundai' => 'Hyundai',
            'infiniti' => 'Infiniti',
            'iveco' => 'Iveco',
            'jaguar' => 'Jaguar',
            'jeep' => 'Jeep',
            'kia' => 'Kia',
            'ktm' => 'KTM',
            'lada' => 'Lada',
            'lamborghini' => 'Lamborghini',
            'lancia' => 'Lancia',
            'land_rover' => 'Land Rover',
            'landwind' => 'Landwind',
            'lexus' => 'Lexus',
            'lotus' => 'Lotus',
            'maserati' => 'Maserati',
            'maybach' => 'Maybach',
            'mazda' => 'Mazda',
            'mclaren' => 'McLaren',
            'mercedes_benz' => 'Mercedes-Benz',
            'mg' => 'MG',
            'mini' => 'Mini',
            'mitsubishi' => 'Mitsubishi',
            'morgan' => 'Morgan',
            'nissan' => 'Nissan',
            'opel' => 'Opel',
            'peugeot' => 'Peugeot',
            'porsche' => 'Porsche',
            'renault' => 'Renault',
            'rolls_royce' => 'Rolls-Royce',
            'rover' => 'Rover',
            'saab' => 'Saab',
            'seat' => 'Seat',
            'skoda' => 'Skoda',
            'smart' => 'Smart',
            'ssangyong' => 'SsangYong',
            'subaru' => 'Subaru',
            'suzuki' => 'Suzuki',
            'tesla' => 'Tesla',
            'toyota' => 'Toyota',
            'volkswagen' => 'Volkswagen',
            'volvo' => 'Volvo',
        ];

        $fuel_types = [
            'gasoline' => 'Gasoline',
            'gasoline_lpg' => 'Gasoline LPG',
            'gasoline_mild_hybrid' => 'Gasoline mild hybrid',
            'gasoline_full_hybrid' => 'Gasoline full hybrid',
            'gasoline_plugin_hybrid' => 'Gasoline plugin hybrid',
            'gasoline_lpg_mild_hybrid' => 'Gasoline LPG mild hybrid',
            'gasoline_lpg_full_hybrid' => 'Gasoline LPG full hybrid',
            'gasoline_lpg_plugin_hybrid' => 'Gasoline LPG plugin hybrid',
            'diesel' => 'Diesel',
            'diesel_lpg' => 'Diesel LPG',
            'diesel_mild_hybrid' => 'Diesel mild hybrid',
            'diesel_full_hybrid' => 'Diesel full hybrid',
            'cng' => 'CNG',
            'electricity' => 'Electricity',
            'hydrogen' => 'Hydrogen',
        ];


        $form = SpladeForm::make()
            ->action(route('account.vehicles.update', $vehicle))
            ->fields([
                Select::make('brand')->label('Brand')->options($brands),
                Input::make('model')->label('Model'),
                Input::make('version')->label('Version'),
                Input::make('engine')->label('Engine'),
                Input::make('factory_specification_fuel_usage')->label('Factory specification fuel usage'),
                Input::make('mileage_start')->label('Mileage start'),
                Date::make('purchase_date')->label('Purchase date'),
                Input::make('license_plate')->label('License plate'),
                Select::make('fuel_type')->label('Fuel type')->options($fuel_types)->class('pb-4'),
                Submit::make()->label('Edit'),
            ])
            ->fill($vehicle)
            ->method('PUT')
            ->class('rounded p-4 bg-white');
 
        return view('account.vehicles.edit', [
            'form' => $form,
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
        Toast::title('Vehicle edited')->autoDismiss(3);

        return to_route('account.vehicles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        Toast::title('Vehicle deleted')->autoDismiss(3);

        return back();
    }
}
