<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(125);

        // Website Settings View Composer
        View::composer('*', function ($view) {
            try {
                $settings = WebsiteSetting::first();
            } catch (\Exception $e) {
                $settings = null;
            }
            $view->with('websiteSettings', $settings);
        });

        // SweetAlert directive
        Blade::directive('sweetalert', function () {
            return <<<'blade'
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (window.SwalAlert) {
                                window.SwalAlert.success('{{ session('success') }}');
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: '{{ session('success') }}',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }
                        });
                    </script>
                @endif

                @if(session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (window.SwalAlert) {
                                window.SwalAlert.error('{{ session('error') }}');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: '{{ session('error') }}',
                                    timer: 4000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }
                        });
                    </script>
                @endif

                @if(session('warning'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (window.SwalAlert) {
                                window.SwalAlert.warning('{{ session('warning') }}');
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Warning!',
                                    text: '{{ session('warning') }}',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }
                        });
                    </script>
                @endif

                @if(session('info'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (window.SwalAlert) {
                                window.SwalAlert.info('{{ session('info') }}');
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Info',
                                    text: '{{ session('info') }}',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }
                        });
                    </script>
                @endif
            blade;
        });
    }
}
