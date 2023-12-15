<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    <!-- Page Content -->
    <main>
        <div class="mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
</div>
