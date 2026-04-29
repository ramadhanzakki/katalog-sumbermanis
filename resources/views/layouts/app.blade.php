<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Sumber Manis')</title>

    {{-- CSS Global --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('foto/logomitra.png') }}" type="image/png">

    {{-- CSS tambahan per-halaman (opsional) --}}
    @stack('styles')

    <style>
        html, body { overscroll-behavior: none; }
    </style>
</head>
<body>

    {{-- Konten utama tiap halaman diisi di sini --}}
    @yield('content')

    {{-- JS Global --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

    {{-- JS tambahan per-halaman diload setelah bootstrap --}}
    @stack('scripts')

</body>
</html>
