<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.head')

    {{-- Page-specific CSS --}}
    @stack('styles')
</head>

<body>

    <main>
        @yield('content')
    </main>

    @include('admin.partials.scripts')

    {{-- Global JS --}}
    @stack('scripts')

</body>
</html>