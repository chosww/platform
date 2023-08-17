<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Two-factor authentication') }}
        </x-slot>

        <x-interpretation name="{{ __('Two-factor authentication', [], 'en') }}" />

        <form class="stack" method="POST" action="{{ localized_route('two-factor.login') }}" x-data="{ recovery: false }"
            novalidate>
            @csrf

            <p x-show="! recovery">
                {{ __('hearth::auth.two_factor_auth_code_intro') }}
            </p>

            <!-- Two-Factor Code -->
            <div class="field" x-show="! recovery">
                <x-hearth-label for="code" :value="__('hearth::auth.label_two_factor_auth_code')" />
                <x-hearth-input name="code" type="text" inputmode="numeric" required autofocus
                    autocomplete="one-time-code" />
            </div>

            <p x-show="! recovery">
                <button class="link" type="button"
                    @click="recovery = ! recovery">{{ __('hearth::auth.two_factor_auth_action_use_recovery_code') }}</button>
            </p>

            <p x-show="recovery">
                {{ __('hearth::auth.two_factor_auth_recovery_code_intro') }}
            </p>

            <!-- Recovery Code -->
            <div class="field" x-show="recovery">
                <x-hearth-label for="recovery_code" :value="__('hearth::auth.label_two_factor_auth_recovery_code')" />
                <x-hearth-input name="recovery_code" type="text" autocomplete="one-time-code" />
            </div>

            <p x-show="recovery">
                <button class="link" type="button"
                    @click="recovery = ! recovery">{{ __('hearth::auth.two_factor_auth_action_use_code') }}</button>
            </p>

            <button>
                {{ __('hearth::auth.sign_in') }}
            </button>
        </form>
    </x-auth-card>
</x-guest-layout>
