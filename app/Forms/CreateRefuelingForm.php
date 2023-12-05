<?php

namespace App\Forms;

use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Radios;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Checkboxes;
use ProtoneMedia\Splade\FormBuilder\Textarea;
use ProtoneMedia\Splade\SpladeForm;
use App\Models\Vehicle;

class CreateRefuelingForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('account.refuelings.store'))
            ->method('POST')
            ->class('space-y-4 p-4 bg-white rounded');
    }

    public function fields(): array
    {

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

        $vehicles = Vehicle::all()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                $brand = ucfirst($vehicle->brand),
                'label' => "{$brand} {$vehicle->model} {$vehicle->version} {$vehicle->engine}",
            ];
        })->pluck('label', 'id')->toArray();

        return [
            Select::make('vehicle_id')
                ->label('Vehicle')
                ->options($vehicles)
                ->class('pb-4'),

            Date::make('date')
                ->label(__('Date')),

            Input::make('gas_station')
                ->label(__('Gas station')),

            Select::make('fuel_type')
                ->label('Fuel type')
                ->options($fuel_types)
                ->class('pb-4'),

            Input::make('amount')
                ->label(__('Amount')),

            Input::make('unit_price')
                ->label(__('Unit price')),

            Input::make('total_price')
                ->label(__('Total price')),

            Input::make('mileage_begin')
                ->label(__('Mileage begin')),

            Input::make('mileage_end')
                ->label(__('Mileage end')),

            Input::make('fuel_usage_onboard_computer')
                ->label(__('Fuel usage onboard computer')),

            Radios::make('tyres')
            ->label('Tyres')
            ->options([
                'summer_tires' => 'Summer tires',
                'winter_tires' => 'Winter tires',
                'all_season_tires' => 'All season tires',
            ])
            ->inline(),

            Checkboxes::make('options')
                ->label('Climate control')
                ->options([
                    'airconditioning' => 'Airconditioning',
                    'heater' => 'Heater',
                    'climate_control' => 'Climate control',
                ])
                ->inline(),

            Checkboxes::make('options')
                ->label('Routes')
                ->options([
                    'city' => 'City',
                    'country_roades' => 'Country roads',
                    'highway' => 'Highway',
                ])
                ->inline(),

            Radios::make('driving_style')
                ->label('Driving style')
                ->options([
                    'slow' => 'Slow',
                    'normal' => 'Normal',
                    'fast' => 'Fast',
                ])
                ->inline(),

            Textarea::make('comments')
                ->label(__('Comments')),

            Submit::make()
                ->label(__('Save')),
        ];
    }
}
