<nav class="container navbar bg-background" style="color: var(--text-color);">
    <!-- Logo Section -->
    <div class="container-fluid d-flex justify-content-between align-items-center">
        @php
            $websiteInfo = websiteInfo();
            $appLogoPath = public_path('app_logo/' . $websiteInfo->app_logo);
            $appLogo = file_exists($appLogoPath) ? asset('app_logo/' . $websiteInfo->app_logo) : asset('default/no-image.png');
        @endphp

        <a class="navbar-brand d-flex align-items-center"
           href="{{ session()->has('user_table') ? route('menu.home', ['tableNumber' => session('user_table')]) : '#' }}">
            <img src="{{ $appLogo }}"
                 alt="{{ $websiteInfo->app_name ?? 'Default App Name' }}"
                 style="max-height: 60px;">
            <span class="ms-2 d-md-block" style="color: var(--text-color);">{{ $websiteInfo->app_name }}</span>
        </a>

        @if(session()->has('user_table'))
            <!-- Hamburger Menu -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars" style="color: var(--text-color);"></i>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="" style="color: var(--text-color);">Home</a>
                        {{-- <a class="nav-link" href="{{ route('menu.home', ['tableNumber' => session('user_table')]) }}" style="color: var(--text-color);">Home</a> --}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="" style="color: var(--text-color);">History</a>
                        {{-- <a class="nav-link" href="{{ route('menu.order.history') }}" style="color: var(--text-color);">History</a> --}}
                    </li>
                    <li>
                        <button class="btn btn-secondary" id="theme-toggle">
                            <i id="theme-icon" class="fas fa-sun" style="color: var(--text-color);"></i>
                        </button>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>
