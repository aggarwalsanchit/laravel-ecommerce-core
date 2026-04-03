<!DOCTYPE html>
<html lang="en">

<head>
    @include('management.partials.head')

    {{-- Page-specific CSS --}}
    @stack('styles')
</head>

<body>

    <main>
        @yield('content')
    </main>

    @include('management.partials.scripts')

    {{-- Global JS --}}
    @stack('scripts')

</body>

</html>
