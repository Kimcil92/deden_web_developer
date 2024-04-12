<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rental Mobil</title>
    <!-- Custom Theme files -->
    <link href="{{ asset('assets/gamecp.css') }}" rel="stylesheet" type="text/css" media="all" />
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    <!-- //Custom Theme files -->
    <!-- web font -->
    <link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
    <!-- //web font -->
</head>
<body>
<!-- main -->
<div class="main-w3layouts wrapper">
    <!-- Modal -->
    <!-- Menampilkan pesan error -->
    <div class="card-body">
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ is_array(session('success')) ? implode(', ', session('success')) : session('success') }}',
                        showConfirmButton: false,
                        timer: 5000
                    });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ is_array(session('error')) ? implode(', ', session('error')) : session('error') }}',
                        showConfirmButton: false,
                        timer: 5000
                    });
                });
            </script>
        @endif
        @yield('section')
    </div>

    <!-- copyright -->
    <div class="colorlibcopy-agile">
        <p>© 2024 Deden Setiawan. All rights reserved | Design by <a href="/" target="_blank">Deden Setiawan</a></p>
    </div>
    <!-- //copyright -->
    <ul class="colorlib-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
<!-- //main -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
</body>
</html>
