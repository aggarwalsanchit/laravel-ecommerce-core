<!DOCTYPE html>
<html lang="en">

<head>
    @include('management.partials.head')

    {{-- Page-specific CSS --}}
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

    {{-- Page-specific JS --}}
    @stack('scripts')

</body>

</html>
