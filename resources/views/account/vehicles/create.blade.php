<x-account-layout>
    <h1 class="text-2x1 font-semibold p-4 flex items-centered"><span class="material-symbols-outlined">add</span> Add vehicle</h1>
    <x-splade-form :action="route('account.vehicles.store')" class="p-4 bg-white rounded-md space-y-2">
        <x-splade-select name="brand" :options="$brands" label="Brand" placeholder="Choose brand" class="pb-3" />
        <x-splade-input name="model" label="Model" />
        <x-splade-input name="version" label="Version" />
        <x-splade-input name="engine" label="Engine" />
        <x-splade-input name="factory_specification_fuel_usage" label="Factory specification fuel usage" />
        <x-splade-input name="mileage_start" label="Mileage start" />
        <x-splade-input name="purchase_date" label="Purchase date" date />
        <x-splade-input name="license_plate" label="License plate" />
        <x-splade-select name="fuel_type" :options="$fuel_types" label="Fuel type" placeholder="Choose fuel type" class="pb-3" />
        <x-splade-submit />
    </x-splade-form>
</x-account-layout>