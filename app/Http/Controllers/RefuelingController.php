<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tables\Refuelings;
use App\Models\Refueling;
use App\Forms\CreateRefuelingForm;
use ProtoneMedia\Splade\Facades\Toast;

class RefuelingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        return view('account.refuelings.index', [
            'refuelings' => Refuelings::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.refuelings.create', [
            'form' => CreateRefuelingForm::class
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

        $data['climate_control'] = json_encode($data['climate_control']);
        $data['routes'] = json_encode($data['routes']);

        Refueling::create($data);

        Toast::title('Refueling added')->autoDismiss(3);

        return to_route('account.refuelings.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
