<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Login - {{ websiteInfo()->app_name }}</title> --}}
    <title>Login - Bhojan Mitra</title>

    <!-- Link AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    {{-- <link rel="icon" type="image/png"
        href="{{ websiteInfo() && websiteInfo()->first() && websiteInfo()->app_logo ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/website.png') }}"> --}}
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            {{-- <img src="{{ websiteInfo()->app_logo ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/no-image.png') }}"
                class='img-circle elevation-2' width="80" height="80" alt=""> --}}
            <br>
            {{-- <a href="{{ route('login') }}"><b>{{ websiteInfo() ? websiteInfo()->app_name : 'Default App Name' }}</b></a> --}}
            {{-- <a href="{{ route('login') }}"><b>{{ websiteInfo() ? websiteInfo()->app_name : 'Default App Name' }}</b></a> --}}
            <a href="{{ route('login') }}"><b>Bhojan Mitra</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input id="email" type="email" class="form-control" name="email"
                            value="{{ old('email') }}" required autofocus placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="input-group mb-3">
                        <input id="password" type="password" class="form-control" name="password" required
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

                @if (Route::has('password.request'))
                    <p class="mb-1">
                        <a href="#">I forgot my password</a>
                    </p>
                @endif
            </div>
        </div>
    </div>


    <!-- AdminLTE JS -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Check for flashed session message and show Toastr notification
            @if (session()->has('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session()->has('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
</body>

</html>
