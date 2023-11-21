<x-guest-layout>
    <x-auth-card>
        <x-splade-form action="{{ route('register') }}" class="space-y-4">
            <x-splade-input id="username" type="text" name="username" :label="__('Username')" required autofocus />
            <x-splade-input id="name" type="text" name="name" :label="__('Name')" required />
            <x-splade-input id="email" type="email" name="email" :label="__('Email')" required />
            <x-splade-input id="password" type="password" name="password" :label="__('Wachtwoord')" required autocomplete="new-password" />
            <x-splade-input id="password_confirmation" type="password" name="password_confirmation" :label="__('Confirm password')" required />
            <x-splade-input id="location" type="text" name="location" :label="__('Location')" />
            <x-splade-input id="birth_date" type="date" name="birth_date" :label="__('Birthdate')" />
            <x-splade-checkbox name="private" value="0" label="Public profile" />

            <div class="flex items-center justify-end">
                <Link class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </Link>

                <x-splade-submit class="ml-4" :label="__('Register')" />
            </div>
        </x-splade-form>
    </x-auth-card>
</x-guest-layout>
