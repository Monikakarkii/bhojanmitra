<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link text-center"
        style="text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 10px 0;">
        <!-- Logo -->
        <img src="{{ file_exists(public_path('app_logo/' . websiteInfo()->app_logo)) ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/no-image.png') }}"
            alt="{{ websiteInfo() ? websiteInfo()->app_name : 'Default App Name' }}"
            class="brand-image img-circle elevation-3"
            style="max-width: 60px; max-height: 60px; margin-bottom: 10px; border: 2px solid #ddd; padding: 5px; border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

        <!-- Website Info -->
        <span class="brand-text font-weight-light" style="font-size: 18px; font-weight: 600;">
            {{ websiteInfo() ? websiteInfo()->app_name : 'Default App Name' }}
        </span>
        <span class="text-sm text-muted" style="font-size: 14px; color: #888;">
            Hello {{ Auth::user()->name }}
        </span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="true">
                <!-- Orders Pending -->
                <li
                    class="nav-item {{ request()->route()->named('kitchen-dashboard') || request()->route()->named('kitchen-dashboardp') ? 'menu-open' : '' }}">
                    <a href="{{ route('kitchen-dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i> <!-- Appropriate icon for pending orders -->
                        <p>Orders Pending</p>
                    </a>
                </li>

                <!-- Orders History -->
                <li class="nav-item {{ request()->route()->named('kitchen.history') ? 'menu-open' : '' }}">
                    <a href="{{ route('kitchen.history') }}" class="nav-link">
                        <i class="nav-icon fas fa-history"></i> <!-- Appropriate icon for history -->
                        <p>Orders History</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
