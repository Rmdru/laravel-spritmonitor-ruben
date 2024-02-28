<x-account-layout>
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold py-4 flex items-centered"><span class="material-symbols-outlined text-2xl">local_gas_station</span>&nbsp;Refuelings</h1>
        <div class="py-4">
            <Link href="{{ route('account.refuelings.create') }}" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded text-white flex items-centered"><span class="material-symbols-outlined">add</span> Add refueling</Link>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <x-splade-table :for="$refuelings">
                @cell('action', $refueling)
                    <div class="space-x-2">
                        <Link href="{{ route('account.refuelings.edit', $refueling) }}" class="text-green-400">
                        Edit
                        </Link>
                        <Link href="{{ route('account.refuelings.destroy', $refueling) }}" method="DELETE" confirm="Delete the refueling" confirm-text="Are you sure?" confirm-button="Yes" cancel-button="No" class="text-red-400 hover:text-red-700 font-semibold">
                        Delete
                        </Link>
                    </div>
                @endcell
            </x-splade-table>
        </div>
    </div>
</x-account-layout>