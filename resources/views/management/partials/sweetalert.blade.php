{{-- resources/views/management/partials/sweetalert.blade.php --}}

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ addslashes(session('success')) }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                showCloseButton: true,
                background: '#28a745',
                color: '#fff'
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ addslashes(session('error')) }}',
                timer: 4000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                showCloseButton: true,
                background: '#dc3545',
                color: '#fff'
            });
        });
    </script>
@endif

@if (session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: '{{ addslashes(session('warning')) }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                showCloseButton: true,
                background: '#ffc107',
                color: '#000'
            });
        });
    </script>
@endif

@if (session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: '{{ addslashes(session('info')) }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                showCloseButton: true
            });
        });
    </script>
@endif
