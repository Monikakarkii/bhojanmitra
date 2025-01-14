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
                <li
                    class="nav-item {{ request()->route()->named('dashboard') || request()->route()->named('dashboard') ? 'menu-open' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li
                    class="nav-item {{ request()->route()->named('site-settings.index') || request()->route()->named('site-settings.index') ? 'menu-open' : '' }}">
                    <a href="{{ route('site-settings.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            Site Settings
                        </p>
                    </a>
                </li>
                {{-- <li
                    class="nav-item {{ request()->route()->named('tables.create') || request()->route()->named('tables.index') || request()->route()->named('tables.edit') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Tables
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('tables.create') }}"
                                class="nav-link {{ request()->route()->named('tables.create') ? 'active' : '' }}">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                <p>Add Table</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tables.index') }}"
                                class="nav-link {{ request()->route()->named('tables.index') ? 'active' : '' }}">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <p>View Tables</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item {{ request()->route()->named('categories.create') || request()->route()->named('categories.index') || request()->route()->named('categories.edit') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i> <!-- Icon for Categories -->
                        <p>
                            Food Categories
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('categories.create') }}"
                                class="nav-link {{ request()->route()->named('categories.create') ? 'active' : '' }}">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                <p>Add Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}"
                                class="nav-link {{ request()->route()->named('categories.index') ? 'active' : '' }}">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <p>View Categories</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item {{ request()->route()->named('menu-items.create') || request()->route()->named('menu-items.index') || request()->route()->named('menu-items.edit') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-utensils"></i> <!-- Icon for Menu Items -->
                        <p>
                            Menu Items
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('menu-items.create') }}"
                                class="nav-link {{ request()->route()->named('menu-items.create') ? 'active' : '' }}">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                <p>Add Menu Item</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('menu-items.index') }}"
                                class="nav-link {{ request()->route()->named('menu-items.index') ? 'active' : '' }}">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <p>View Menu Items</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item {{ request()->route()->named('orders.index') || request()->route()->named('orders.edit') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i> <!-- Updated Icon for Orders -->
                        <p>
                            Orders
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('orders.index') }}"
                                class="nav-link {{ request()->route()->named('orders.index') ? 'active' : '' }}">
                                <i class="fas fa-eye"></i> <!-- Updated Icon for Viewing Orders -->
                                <p>View Orders</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item {{ request()->route()->named('sales.index') || request()->route()->named('sales.index') ? 'menu-open' : '' }}">
                    <a href="{{ route('sales.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Sales
                        </p>
                    </a>
                </li> --}}


            </ul>
        </nav>
    </div>
</aside>
