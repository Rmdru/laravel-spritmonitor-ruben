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
            'euro_95' => 'Euro 95 (E10)',
            'euro_98' => 'Euro 98 (E5)',
            'euro_102' => 'Euro 102',
            'diesel' => 'Diesel',
            'lpg' => 'LPG',
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
                'summer_tyres' => 'Summer tyres',
                'winter_tyres' => 'Winter tyres',
                'all_season_tyres' => 'All season tyres',
            ])
            ->inline(),

            Checkboxes::make('climate_control')
                ->label('Climate control')
                ->options([
                    'airconditioning' => 'Airconditioning',
                    'heater' => 'Heater',
                    'climate_control' => 'Climate control',
                ])
                ->inline(),

            Checkboxes::make('routes')
                ->label('Routes')
                ->options([
                    'city' => 'City',
                    'country_roads' => 'Country roads',
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
