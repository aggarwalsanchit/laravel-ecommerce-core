{{-- resources/views/management/partials/scripts.blade.php --}}
<!-- Vendor js -->
<script src="{{ asset('adminpanel/assets/js/vendor.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('adminpanel/assets/js/app.js') }}"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Other global scripts -->
<script>
    // Global AJAX setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize tooltips
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('[data-bs-toggle="popover"]').popover();
    });
</script>
