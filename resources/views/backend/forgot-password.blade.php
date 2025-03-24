<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ websiteInfo()->app_name }}</title>

    <!-- Link AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
    <link rel="icon" type="image/png"
        href="{{ websiteInfo() && websiteInfo()->first() && websiteInfo()->app_logo ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/website.png') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ websiteInfo()->app_logo ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/no-image.png') }}"
                class='img-circle elevation-2' width="80" height="80" alt="App Logo">
            <br>
            <a href="{{ route('login') }}"><b>{{ websiteInfo() ? websiteInfo()->app_name : 'Default App Name' }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">You forgot your password? Enter your email to receive a reset link.</p>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="row mt-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>

    <!-- AdminLTE & jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            @if (session()->has('status'))
                toastr.success('{{ session('status') }}');
            @elseif (session()->has('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
</body>

</html>
