<!DOCTYPE html>
<html lang="en">

<head>
    @include('management.partials.head')
    @stack('styles')
</head>

<body>
    @include('management.partials.side_menu')
    @include('management.partials.header')
    @include('management.partials.searchModal')

    <main>
        @yield('content')
    </main>

    @include('management.partials.footer')
    @include('management.partials.scripts')

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('management.partials.sweetalert')
    @stack('scripts')
</body>

</html>
