<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>@yield('title', 'KNOTELLE Admin Panel')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/Logo_1.png') }}?v=2">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tabulator CSS -->
    <link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/datatable.css') }}">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>


    <script>
        // Global variables
        const BASE_URL = '{{ url('/') }}';
        const ADMIN_URL = '{{ url('/admin') }}';
        const ASSET_URL = '{{ asset('') }}';
        window.ADMIN_API_TOKEN = @json(session('admin_api_token'));
    </script>
</head>

<body class="bg-stone-50">
    @if (request()->is('admin/*') && !request()->is('admin/login'))
        @include('admin.partials.sidebar')
    @endif

    <div id="main-content" class="transition-all duration-300 @if (request()->is('admin/*') && !request()->is('admin/login')) ml-0 md:ml-24 @endif">
        @if (request()->is('admin/*') && !request()->is('admin/login'))
            @include('admin.partials.header')
        @endif

        <main class="@if (request()->is('admin/*') && !request()->is('admin/login')) p-4 sm:p-6 md:p-8 @endif">
            @yield('content')
        </main>
    </div>

    <!-- Common Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Tabulator JS -->
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>

    <!-- Lodash -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="{{ asset('js/admin/custom.js') }}?v={{ filemtime(public_path('js/admin/custom.js')) }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.0/axios.min.js"></script>
    <script>
        if (window.axios) {
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
            }
        }
    </script>




    <script>
        function broadcastMediaUpdate() {
            const timestamp = Date.now();
            try {
                localStorage.setItem('knotelle_media_updated', timestamp.toString());
                localStorage.setItem('knotelle_media_sync', timestamp.toString());
                localStorage.setItem('knotelle_contact_updated', timestamp.toString());
                localStorage.setItem('knotelle_about_updated', timestamp.toString());
            } catch(e){}
            try {
                if ('BroadcastChannel' in window) {
                    const channel = new BroadcastChannel('knotelle_media_sync');
                    channel.postMessage({ type: 'MEDIA_UPDATED', timestamp: timestamp });
                    setTimeout(() => {
                        try { channel.close(); } catch(e) {}
                    }, 200);
                }
            } catch(e){}
        }
    </script>

    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            toastr.success("{{ session('success') }}");
            broadcastMediaUpdate();
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            toastr.error("{{ session('error') }}");
        });
    </script>
    @endif

    @stack('scripts')
</body>

</html>
