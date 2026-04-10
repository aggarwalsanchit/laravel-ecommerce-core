<!-- Begin page -->
<div class="wrapper">
    <!-- Sidenav Menu Start -->
    <div class="sidenav-menu">

        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="logo">
            @php
                use App\Models\WebsiteSetting;
                use Illuminate\Support\Facades\Storage;

                $settings = WebsiteSetting::first();

                // Get logo light (large)
                $logoLightLarge = 'assets/images/logo.png';
                if ($settings && $settings->logo_light && Storage::disk('public')->exists($settings->logo_light)) {
                    $logoLightLarge = asset('storage/' . $settings->logo_light);
                } elseif (!file_exists(public_path('assets/images/logo.png'))) {
                    $logoLightLarge = asset('dummy-admin-logo.webp');
                }

                // Get logo dark (large)
                $logoDarkLarge = 'assets/images/logo-dark.png';
                if ($settings && $settings->logo_dark && Storage::disk('public')->exists($settings->logo_dark)) {
                    $logoDarkLarge = asset('storage/' . $settings->logo_dark);
                } elseif (!file_exists(public_path('assets/images/logo-dark.png'))) {
                    $logoDarkLarge = asset('dummy-admin-logo.webp');
                }

                // Get small logo
                $logoSmall = 'assets/images/logo-sm.png';
                if ($settings && $settings->logo_sidebar && Storage::disk('public')->exists($settings->logo_sidebar)) {
                    $logoSmall = asset('storage/' . $settings->logo_sidebar);
                } elseif (!file_exists(public_path('assets/images/logo-sm.png'))) {
                    $logoSmall = asset('dummy-admin-logo.webp');
                }
            @endphp

            <span class="logo-light">
                <span class="logo-lg"><img src="{{ $logoLightLarge }}"
                        alt="{{ $settings->logo_light_alt_tag ?? 'Logo' }}"></span>
                <span class="logo-sm text-center"><img src="{{ $logoSmall }}"
                        alt="{{ $settings->logo_small_alt_tag ?? 'Logo' }}"></span>
            </span>

            <span class="logo-dark">
                <span class="logo-lg"><img src="{{ $logoDarkLarge }}"
                        alt="{{ $settings->logo_dark_alt_tag ?? 'Logo' }}"></span>
                <span class="logo-sm text-center"><img src="{{ $logoSmall }}"
                        alt="{{ $settings->logo_small_alt_tag ?? 'Logo' }}"></span>
            </span>
        </a>

        <!-- Sidebar Hover Menu Toggle Button -->
        <button class="button-sm-hover">
            <i class="ti ti-circle align-middle"></i>
        </button>

        <!-- Full Sidebar Menu Close Button -->
        <button class="button-close-fullsidebar">
            <i class="ti ti-x align-middle"></i>
        </button>

        <div data-simplebar>

            <!--- Sidenav Menu -->
            <ul class="side-nav">
                @if (Auth::guard('admin')->check())
                    @can('view_dashboard', 'admin')
                        <li class="side-nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="side-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                                <span class="menu-text"> Dashboard </span>
                            </a>
                        </li>
                    @endcan

                    <li class="side-nav-title mt-2">Apps & Pages</li>
                    @canany(['view_users', 'view_permissions', 'view_roles'], 'admin')
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                                aria-controls="sidebarEcommerce"
                                class="side-nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <span class="menu-icon"><i class="ti ti-user-filled"></i></span>
                                <span class="menu-text"> Users Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce">
                                <ul class="sub-menu">
                                    @can('view_users', 'admin')
                                        <li
                                            class="side-nav-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.users.index') }}" class="side-nav-link">
                                                <span class="menu-text">Users</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view_permissions', 'admin')
                                        <li
                                            class="side-nav-item {{ request()->routeIs('admin.permissions.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.permissions.index') }}" class="side-nav-link">
                                                <span class="menu-text">Permissions</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view_roles', 'admin')
                                        <li
                                            class="side-nav-item {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.roles.index') }}" class="side-nav-link">
                                                <span class="menu-text">Roles</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany
                    @can('view_vendors', 'admin')
                        <li class="side-nav-item">
                            <a href="{{ route('admin.vendors.index') }}" class="side-nav-link">
                                <span class="menu-icon"><i class="ti ti-users"></i></span>
                                <span class="menu-text"> Vendors </span>
                            </a>
                        </li>
                    @endcan
                    @can('view_categories', 'admin')
                        <li class="side-nav-item">
                            <a href="{{ route('admin.categories.index') }}"
                                class="side-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                                <span class="menu-text"> Categories </span>
                            </a>
                        </li>
                    @endcan
                    @can('view_colors', 'admin')
                        <li class="side-nav-item">
                            <a href="{{ route('admin.colors.index') }}"
                                class="side-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                                <span class="menu-text"> Colors </span>
                            </a>
                        </li>
                    @endcan
                    @can('view_sizes', 'admin')
                        <li class="side-nav-item">
                            <a href="{{ route('admin.sizes.index') }}"
                                class="side-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                                <span class="menu-text"> Sizes </span>
                            </a>
                        </li>
                    @endcan
                    @can('view_attributes', 'admin')
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                                aria-controls="sidebarEcommerce" class="side-nav-link">
                                <span class="menu-icon"><i class="ti ti-user-filled"></i></span>
                                <span class="menu-text"> Custom Attributes </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce">
                                <ul class="sub-menu">
                                    <li class="side-nav-item">
                                        <a href="{{ route('admin.attributes.index') }}" class="side-nav-link">
                                            <span class="menu-text">Add Attributes</span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a href="{{ route('admin.attribute-groups.index') }}" class="side-nav-link">
                                            <span class="menu-text">Attributes Groups</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endcan
                    @can('view_discounts', 'admin')
                        <li class="side-nav-item">
                            <a href="{{ route('admin.discounts.index') }}" class="side-nav-link">
                                <span class="menu-icon"><i class="ti ti-folder-filled"></i></span>
                                <span class="menu-text"> Discounts </span>
                            </a>
                        </li>
                    @endcan
                    @can('view_products', 'admin')
                        <li class="side-nav-item">
                            <a href="{{ route('admin.products.index') }}" class="side-nav-link">
                                <span class="menu-icon"><i class="ti ti-folder-filled"></i></span>
                                <span class="menu-text"> Products </span>
                            </a>
                        </li>
                    @endcan
                @elseif(Auth::guard('vendor')->check())
                    @php $vendor = auth()->guard('vendor')->user(); @endphp
                    @if ($vendor->can('view_dashboard'))
                        <li class="side-nav-item">
                            <a href="{{ route('vendor.dashboard') }}"
                                class="side-nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                                <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                                <span class="menu-text"> Dashboard </span>
                            </a>
                        </li>
                    @endif
                    <li class="side-nav-title mt-2">Apps & Pages</li>
                    {{-- Complete Profile Menu (Show for pending vendors OR if profile not complete) --}}
                    @if ($vendor->shop->account_status === 'pending' || $vendor->shop->profile_completed < 70)
                        @if ($vendor->can('complete_profile'))
                            <li class="side-nav-item">
                                <a href="{{ route('vendor.complete-profile') }}"
                                    class="side-nav-link {{ request()->routeIs('vendor.complete-profile') ? 'active' : '' }}">
                                    <span class="menu-icon"><i class="ti ti-edit"></i></span>
                                    <span class="menu-text">Complete Profile</span>
                                    @if ($vendor->profile_completed > 0)
                                        <span class="badge bg-warning ms-0">{{ $vendor->profile_completed }}%</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endif

                    @if ($vendor->can('view_staff'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                                aria-controls="sidebarEcommerce" class="side-nav-link">
                                <span class="menu-icon"><i class="ti ti-user-filled"></i></span>
                                <span class="menu-text"> Staff </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce">
                                <ul class="sub-menu">
                                    <li class="side-nav-item">
                                        <a href="{{ route('vendor.staff.index') }}" class="side-nav-link">
                                            <span class="menu-text">Staff</span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a href="{{ route('vendor.permissions.index') }}" class="side-nav-link">
                                            <span class="menu-text">Permissions</span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a href="{{ route('vendor.roles.index') }}" class="side-nav-link">
                                            <span class="menu-text">Roles</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                    @endif
                    {{-- <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                            aria-controls="sidebarEcommerce" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-user-filled"></i></span>
                            <span class="menu-text"> Product Attributes </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarEcommerce">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{{ route('vendor.categories.index') }}" class="side-nav-link">
                                        <span class="menu-text">Categories</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('vendor.sizes.index') }}" class="side-nav-link">
                                        <span class="menu-text">Sizes</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('vendor.colors.index') }}" class="side-nav-link">
                                        <span class="menu-text">Colour</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                            aria-controls="sidebarEcommerce" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-user-filled"></i></span>
                            <span class="menu-text"> Custom Attributes </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarEcommerce">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{{ route('vendor.attributes.index') }}" class="side-nav-link">
                                        <span class="menu-text">Add Attributes</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('vendor.attribute-groups.index') }}" class="side-nav-link">
                                        <span class="menu-text">Attributes Groups</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('vendor.discounts.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-folder-filled"></i></span>
                            <span class="menu-text"> Discount </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('vendor.products.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-folder-filled"></i></span>
                            <span class="menu-text"> Products </span>
                        </a>
                    </li> --}}
                @endif
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                        aria-controls="sidebarEcommerce" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-basket-filled"></i></span>
                        <span class="menu-text"> Ecommerce </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarEcommerce">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-products.html" class="side-nav-link">
                                    <span class="menu-text">Products</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-products-grid.html" class="side-nav-link">
                                    <span class="menu-text">Products Grid</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-product-details.html" class="side-nav-link">
                                    <span class="menu-text">Product Details</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-products-add.html" class="side-nav-link">
                                    <span class="menu-text">Add Products</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-categories.html" class="side-nav-link">
                                    <span class="menu-text">Categories</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-orders.html" class="side-nav-link">
                                    <span class="menu-text">Orders</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-order-details.html" class="side-nav-link">
                                    <span class="menu-text">Order Details</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-customers.html" class="side-nav-link">
                                    <span class="menu-text">Customers</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-ecommerce-sellers.html" class="side-nav-link">
                                    <span class="menu-text">Sellers</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarInvoice" aria-expanded="false"
                        aria-controls="sidebarInvoice" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-file-invoice"></i></span>
                        <span class="menu-text"> Invoice</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarInvoice">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="apps-invoices.html" class="side-nav-link">
                                    <span class="menu-text">Invoices</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-invoice-details.html" class="side-nav-link">
                                    <span class="menu-text">View Invoice</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="apps-invoice-create.html" class="side-nav-link">
                                    <span class="menu-text">Create Invoice</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false"
                        aria-controls="sidebarPages" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-files"></i></span>
                        <span class="menu-text"> Pages </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarPages">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="pages-starter.html" class="side-nav-link">
                                    <span class="menu-text">Starter Page</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="pages-faq.html" class="side-nav-link">
                                    <span class="menu-text">FAQ</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="pages-maintenance.html" class="side-nav-link">
                                    <span class="menu-text">Maintenance</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="pages-timeline.html" class="side-nav-link">
                                    <span class="menu-text">Timeline</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="pages-coming-soon.html" class="side-nav-link">
                                    <span class="menu-text">Coming Soon</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="pages-pricing.html" class="side-nav-link">
                                    <span class="menu-text">Pricing</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="pages-terms-conditions.html" class="side-nav-link">
                                    <span class="menu-text">Terms & Conditions</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarPagesAuth" aria-expanded="false"
                        aria-controls="sidebarPagesAuth" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-lock-filled"></i></span>
                        <span class="menu-text"> Auth Pages </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarPagesAuth">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="auth-login.html" class="side-nav-link">
                                    <span class="menu-text">Login</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-register.html" class="side-nav-link">
                                    <span class="menu-text">Register</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-logout.html" class="side-nav-link">
                                    <span class="menu-text">Logout</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-recoverpw.html" class="side-nav-link">
                                    <span class="menu-text">Recover Password</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-createpw.html" class="side-nav-link">
                                    <span class="menu-text">Create Password</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-lock-screen.html" class="side-nav-link">
                                    <span class="menu-text">Lock Screen</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-confirm-mail.html" class="side-nav-link">
                                    <span class="menu-text">Confirm Mail</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-login-pin.html" class="side-nav-link">
                                    <span class="menu-text">Login with PIN</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-2fa.html" class="side-nav-link">
                                    <span class="menu-text">2FA</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="auth-account-deactivation.html" class="side-nav-link">
                                    <span class="menu-text">Account Deactivation</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarPagesError" aria-expanded="false"
                        aria-controls="sidebarPagesError" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-server-2"></i></span>
                        <span class="menu-text"> Error Pages </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarPagesError">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="error-401.html" class="side-nav-link">
                                    <span class="menu-text">401 Unauthorized</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-400.html" class="side-nav-link">
                                    <span class="menu-text">400 Bad Request</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-403.html" class="side-nav-link">
                                    <span class="menu-text">403 Forbidden</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-404.html" class="side-nav-link">
                                    <span class="menu-text">404 Not Found</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-408.html" class="side-nav-link">
                                    <span class="menu-text">408 Request Timeout</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-500.html" class="side-nav-link">
                                    <span class="menu-text">500 Internal Server</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-501.html" class="side-nav-link">
                                    <span class="menu-text">501 Not Implemented</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-502.html" class="side-nav-link">
                                    <span class="menu-text">502 Service Overloaded</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-service-unavailable.html" class="side-nav-link">
                                    <span class="menu-text">Service Unavailable</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="error-404-alt.html" class="side-nav-link">
                                    <span class="menu-text">Error 404 Alt</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-title mt-2">Components</li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarBaseUI" aria-expanded="false"
                        aria-controls="sidebarBaseUI" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-brightness-filled"></i></span>
                        <span class="menu-text"> Base UI </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarBaseUI">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="ui-accordions.html" class="side-nav-link">
                                    <span class="menu-text">Accordions</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-alerts.html" class="side-nav-link">
                                    <span class="menu-text">Alerts</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-avatars.html" class="side-nav-link">
                                    <span class="menu-text">Avatars</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-badges.html" class="side-nav-link">
                                    <span class="menu-text">Badges</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-breadcrumb.html" class="side-nav-link">
                                    <span class="menu-text">Breadcrumb</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-buttons.html" class="side-nav-link">
                                    <span class="menu-text">Buttons</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-cards.html" class="side-nav-link">
                                    <span class="menu-text">Cards</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-carousel.html" class="side-nav-link">
                                    <span class="menu-text">Carousel</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-collapse.html" class="side-nav-link">
                                    <span class="menu-text">Collapse</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-dropdowns.html" class="side-nav-link">
                                    <span class="menu-text">Dropdowns</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-ratios.html" class="side-nav-link">
                                    <span class="menu-text">Ratios</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-grid.html" class="side-nav-link">
                                    <span class="menu-text">Grid</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-links.html" class="side-nav-link">
                                    <span class="menu-text">Links</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-list-group.html" class="side-nav-link">
                                    <span class="menu-text">List Group</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-modals.html" class="side-nav-link">
                                    <span class="menu-text">Modals</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-notifications.html" class="side-nav-link">
                                    <span class="menu-text">Notifications</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-offcanvas.html" class="side-nav-link">
                                    <span class="menu-text">Offcanvas</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-placeholders.html" class="side-nav-link">
                                    <span class="menu-text">Placeholders</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-pagination.html" class="side-nav-link">
                                    <span class="menu-text">Pagination</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-popovers.html" class="side-nav-link">
                                    <span class="menu-text">Popovers</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-progress.html" class="side-nav-link">
                                    <span class="menu-text">Progress</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-scrollspy.html" class="side-nav-link">
                                    <span class="menu-text">Scrollspy</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-spinners.html" class="side-nav-link">
                                    <span class="menu-text">Spinners</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-tabs.html" class="side-nav-link">
                                    <span class="menu-text">Tabs</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-tooltips.html" class="side-nav-link">
                                    <span class="menu-text">Tooltips</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-typography.html" class="side-nav-link">
                                    <span class="menu-text">Typography</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="ui-utilities.html" class="side-nav-link">
                                    <span class="menu-text">Utilities</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarExtendedUI" aria-expanded="false"
                        aria-controls="sidebarExtendedUI" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-alien-filled"></i></span>
                        <span class="menu-text"> Extended UI </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarExtendedUI">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="extended-dragula.html" class="side-nav-link">
                                    <span class="menu-text">Dragula</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="extended-sweetalerts.html" class="side-nav-link">
                                    <span class="menu-text">Sweet Alerts</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="extended-ratings.html" class="side-nav-link">
                                    <span class="menu-text">Ratings</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="extended-scrollbar.html" class="side-nav-link">
                                    <span class="menu-text">Scrollbar</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarIcons" aria-expanded="false"
                        aria-controls="sidebarIcons" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-leaf"></i></span>
                        <span class="menu-text"> Icons </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarIcons">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="icons-tabler.html" class="side-nav-link">
                                    <span class="menu-text">Tabler</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="icons-solar.html" class="side-nav-link">
                                    <span class="menu-text">Solar</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarCharts" aria-expanded="false"
                        aria-controls="sidebarCharts" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-chart-arcs"></i></span>
                        <span class="menu-text"> Charts </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarCharts">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="charts-apex-area.html" class="side-nav-link">
                                    <span class="menu-text">Area</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-bar.html" class="side-nav-link">
                                    <span class="menu-text">Bar</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-bubble.html" class="side-nav-link">
                                    <span class="menu-text">Bubble</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-candlestick.html" class="side-nav-link">
                                    <span class="menu-text">Candlestick</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-column.html" class="side-nav-link">
                                    <span class="menu-text">Column</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-heatmap.html" class="side-nav-link">
                                    <span class="menu-text">Heatmap</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-line.html" class="side-nav-link">
                                    <span class="menu-text">Line</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-mixed.html" class="side-nav-link">
                                    <span class="menu-text">Mixed</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-timeline.html" class="side-nav-link">
                                    <span class="menu-text">Timeline</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-boxplot.html" class="side-nav-link">
                                    <span class="menu-text">Boxplot</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-treemap.html" class="side-nav-link">
                                    <span class="menu-text">Treemap</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-pie.html" class="side-nav-link">
                                    <span class="menu-text">Pie</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-radar.html" class="side-nav-link">
                                    <span class="menu-text">Radar</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-radialbar.html" class="side-nav-link">
                                    <span class="menu-text">RadialBar</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-scatter.html" class="side-nav-link">
                                    <span class="menu-text">Scatter</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-polar-area.html" class="side-nav-link">
                                    <span class="menu-text">Polar Area</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="charts-apex-sparklines.html" class="side-nav-link">
                                    <span class="menu-text">Sparklines</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarForms" aria-expanded="false"
                        aria-controls="sidebarForms" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-forms"></i></span>
                        <span class="menu-text"> Forms </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarForms">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="form-elements.html" class="side-nav-link">
                                    <span class="menu-text">Basic Elements</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-inputmask.html" class="side-nav-link">
                                    <span class="menu-text">Inputmask</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-picker.html" class="side-nav-link">
                                    <span class="menu-text">Picker</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-select.html" class="side-nav-link">
                                    <span class="menu-text">Select</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-range-slider.html" class="side-nav-link">
                                    <span class="menu-text">Range Slider</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-validation.html" class="side-nav-link">
                                    <span class="menu-text">Validation</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-wizard.html" class="side-nav-link">
                                    <span class="menu-text">Wizard</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-fileuploads.html" class="side-nav-link">
                                    <span class="menu-text">File Uploads</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-editors.html" class="side-nav-link">
                                    <span class="menu-text">Editors</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="form-layouts.html" class="side-nav-link">
                                    <span class="menu-text">Layouts</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarTables" aria-expanded="false"
                        aria-controls="sidebarTables" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-table-filled"></i></span>
                        <span class="menu-text"> Tables </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarTables">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="tables-basic.html" class="side-nav-link">
                                    <span class="menu-text">Basic Tables</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="tables-gridjs.html" class="side-nav-link">
                                    <span class="menu-text">Gridjs Tables</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarMaps" aria-expanded="false"
                        aria-controls="sidebarMaps" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-map-pin-filled"></i></span>
                        <span class="menu-text"> Maps </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarMaps">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="maps-google.html" class="side-nav-link">
                                    <span class="menu-text">Google Maps</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="maps-vector.html" class="side-nav-link">
                                    <span class="menu-text">Vector Maps</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="maps-leaflet.html" class="side-nav-link">
                                    <span class="menu-text">Leaflet Maps</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-title mt-2">
                    More
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts" aria-expanded="false"
                        aria-controls="sidebarLayouts" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-layout-filled"></i></span>
                        <span class="menu-text"> Layouts </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarLayouts">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="layouts-horizontal.html" target="_blank"
                                    class="side-nav-link">Horizontal</a>
                            </li>
                            <li class="side-nav-item">
                                <a href="layouts-full.html" target="_blank" class="side-nav-link">Full View</a>
                            </li>
                            <li class="side-nav-item">
                                <a href="layouts-fullscreen.html" target="_blank" class="side-nav-link">Fullscreen
                                    View</a>
                            </li>
                            <li class="side-nav-item">
                                <a href="layouts-hover.html" target="_blank" class="side-nav-link">Hover Menu</a>
                            </li>
                            <li class="side-nav-item">
                                <a href="layouts-compact.html" target="_blank" class="side-nav-link">Compact</a>
                            </li>
                            <li class="side-nav-item">
                                <a href="layouts-icon-view.html" target="_blank" class="side-nav-link">Icon View</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false"
                        aria-controls="sidebarMultiLevel" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-box-multiple-3"></i></span>
                        <span class="menu-text"> Multi Level </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarMultiLevel">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarSecondLevel" aria-expanded="false"
                                    aria-controls="sidebarSecondLevel" class="side-nav-link">
                                    <span class="menu-text"> Second Level </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarSecondLevel">
                                    <ul class="sub-menu">
                                        <li class="side-nav-item">
                                            <a href="javascript: void(0);" class="side-nav-link">
                                                <span class="menu-text">Item 1</span>
                                            </a>
                                        </li>
                                        <li class="side-nav-item">
                                            <a href="javascript: void(0);" class="side-nav-link">
                                                <span class="menu-text">Item 2</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarThirdLevel" aria-expanded="false"
                                    aria-controls="sidebarThirdLevel" class="side-nav-link">
                                    <span class="menu-text"> Third Level </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarThirdLevel">
                                    <ul class="sub-menu">
                                        <li class="side-nav-item">
                                            <a href="javascript: void(0);" class="side-nav-link">Item 1</a>
                                        </li>
                                        <li class="side-nav-item">
                                            <a data-bs-toggle="collapse" href="#sidebarFourthLevel"
                                                aria-expanded="false" aria-controls="sidebarFourthLevel"
                                                class="side-nav-link">
                                                <span class="menu-text"> Item 2 </span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <div class="collapse" id="sidebarFourthLevel">
                                                <ul class="sub-menu">
                                                    <li class="side-nav-item">
                                                        <a href="javascript: void(0);" class="side-nav-link">
                                                            <span class="menu-text">Item 2.1</span>
                                                        </a>
                                                    </li>
                                                    <li class="side-nav-item">
                                                        <a href="javascript: void(0);" class="side-nav-link">
                                                            <span class="menu-text">Item 2.2</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

            <div class="clearfix"></div>
        </div>
    </div>
    <!-- Sidenav Menu End -->
