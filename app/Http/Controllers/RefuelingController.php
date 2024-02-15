<?php

namespace App\Http\Controllers;

use App\Forms\CreateRefuelingForm;
use App\Forms\UpdateRefuelingForm;
use App\Models\Refueling;
use App\Tables\Refuelings;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\Facades\Toast;

class RefuelingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('account.refuelings.index', [
            'refuelings' => Refuelings::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.refuelings.create', [
            'form' => CreateRefuelingForm::class,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateRefuelingForm $form)
    {
        $data = $form->validate($request);

        $last_mileage_end = Refueling::latest('mileage_end')->value('mileage_end');
        $mileage_begin = $data['mileage_begin'] ?? $last_mileage_end;

        $distance_traveled = $data['mileage_end'] - $mileage_begin;
        $fuel_usage = $data['amount'] / $distance_traveled * 100;
        $data['fuel_usage'] = $fuel_usage;

        if ($distance_traveled > 0) {
            $fuel_usage = $data['amount'] / $distance_traveled * 100;

            $cost_per_kilometer = $data['amount'] / $distance_traveled;

            $data['fuel_usage'] = $fuel_usage;
            $data['costs_per_kilometer'] = $cost_per_kilometer;
        } else {
            $data['fuel_usage'] = 0;
            $data['costs_per_kilometer'] = 0;
        }

        $data['climate_control'] = isset($data['climate_control']) && is_array($data['climate_control']) ? json_encode($data['climate_control']) : json_encode([]);
        $data['routes'] = isset($data['routes']) && is_array($data['routes']) ? json_encode($data['routes']) : json_encode([]);

        Refueling::create($data);

        Toast::title('Refueling added')->autoDismiss(3);

        return to_route('account.refuelings.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Refueling $refueling)
    {
        $refueling->climate_control = json_decode($refueling->climate_control);
        $refueling->routes = json_decode($refueling->routes);

        return view('account.refuelings.edit', [
            'form' => UpdateRefuelingForm::make()
                ->action(route('account.refuelings.update', $refueling))
                ->fill($refueling),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Refueling $refueling, UpdateRefuelingForm $form)
    {
        $data = $form->validate($request);

        $distance_traveled = $data['mileage_end'] - $data['mileage_begin'];
        $fuel_usage = $data['amount'] / $distance_traveled * 100;
        $data['fuel_usage'] = $fuel_usage;

        if ($distance_traveled > 0) {
            $fuel_usage = $data['amount'] / $distance_traveled * 100;

            $cost_per_kilometer = $data['amount'] / $distance_traveled;

            $data['fuel_usage'] = $fuel_usage;
            $data['costs_per_kilometer'] = $cost_per_kilometer;
        } else {
            $data['fuel_usage'] = 0;
            $data['costs_per_kilometer'] = 0;
        }

        $data['climate_control'] = isset($data['climate_control']) && is_array($data['climate_control']) ? json_encode($data['climate_control']) : json_encode([]);
        $data['routes'] = isset($data['routes']) && is_array($data['routes']) ? json_encode($data['routes']) : json_encode([]);

        $refueling->update($data);

        Toast::title('Refueling edited')->autoDismiss(3);

        return to_route('account.refuelings.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Refueling $refueling)
    {
        $refueling->delete();

        Toast::title('Refueling deleted')->autoDismiss(3);

        return back();
    }
}
