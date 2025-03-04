        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? __('app.name') }} &mdash; {{ __('app.name') }}</title>
        <meta name="description" content="{{ __('app.description') }}">
        <meta name="theme-color" content="#fff" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#000" media="(prefers-color-scheme: dark)">

        <!-- Manifest -->
        <link rel="manifest" href="{{ asset('/manifest.webmanifest') }}" crossorigin="use-credentials">

        <!-- Icons -->
        <link rel="icon" href="{{ asset('/favicon.ico') }}">
        <link rel="icon" href="{{ asset('/icon.svg') }}" type="image/svg+xml">
        <link rel="apple-touch-icon" href="{{ asset('/apple-touch-icon.png') }}">

        <!-- Styles -->
        @vite('resources/css/app.css')
        @googlefonts
        @livewireStyles()

        <!-- Scripts -->
        <script>document.documentElement.className = document.documentElement.className.replace("no-js", "js");</script>
        @vite('resources/js/app.js')

