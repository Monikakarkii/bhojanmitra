<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ websiteInfo()->app_name }}</title>

    <!-- Link AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="icon" type="image/png"
        href="{{ websiteInfo() && websiteInfo()->first() && websiteInfo()->app_logo ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/website.png') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ websiteInfo()->app_logo ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/no-image.png') }}"
                class='img-circle elevation-2' width="80" height="80" alt="">
            <br>
            <a href="{{ route('login') }}"><b>{{ websiteInfo() ? websiteInfo()->app_name : 'Default App Name' }}</b></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
              <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

              <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="New Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Change Password</button>
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

