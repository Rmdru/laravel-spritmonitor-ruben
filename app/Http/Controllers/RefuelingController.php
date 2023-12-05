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
    public function store(Request $request, CreateStateForm $form)
    {
        $data = $form->validate($request);
        State::create($data);

        Toast::title('Refueling added')->autoDismiss(3);

        return to_route('account.vehicles.index');
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
