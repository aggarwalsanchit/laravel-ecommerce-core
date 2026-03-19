<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.head')

    {{-- Page-specific CSS --}}
    @stack('styles')
</head>

<body>
    @include('admin.partials.side_menu')
    @include('admin.partials.header')
    @include('admin.partials.searchModal')

    <main>
        @yield('content')
    </main>

    @include('admin.partials.footer')

    @include('admin.partials.scripts')

    {{-- Page-specific JS --}}
    @stack('scripts')

</body>
</html>