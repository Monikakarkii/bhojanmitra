<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ websiteInfo()->app_name }} - @yield('title', 'Default Title')</title>

    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
    {{--    link rel="stylesheet" href="{{asset('adminlte/plugins/select2/css/select2.min.css')}}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/token_input_bootstrap/css/bootstrap-tokenfield.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/ekko-lightbox/ekko-lightbox.css') }}">
    <link rel="icon" type="image/png"
        href="{{ websiteInfo() && websiteInfo()->first() && websiteInfo()->app_logo ? asset('app_logo/' . websiteInfo()->app_logo) : asset('default/website.png') }}">

    <!-- Add dynamic theme color CSS -->
    <style>
        :root {
            --primary-color: {{ $siteSetting->theme_color_primary ?? '#007bff' }};
            --secondary-color: {{ $siteSetting->theme_color_secondary ?? '#6c757d' }};
        }
    </style>
    @yield('customCss')



</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('backend.layout.navbar')

        <!-- Sidebar -->
        @include('backend.layout.sidebar')

        <!-- Content Wrapper -->
        <section class="content">
            @yield('content')

        </section>

        <!-- Footer -->
        @include('backend.layout.footer')

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/token_input_bootstrap/bootstrap-tokenfield.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/ekko-lightbox/ekko-lightbox.js') }}"></script>
    <!-- Select2 -->
    {{-- <script src="{{asset('adminlte/plugins/select2/js/select2.full.min.js')}}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Data!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2();
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>

    @yield('customJs')
</body>

</html>
