<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Le Sénévé ● {{ $title ?? 'Dashboard' }}</title>

    <meta name="description" content="Le Sénévé - Application de gestion">
    <meta name="author" content="Relwindé">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
    <!-- END Stylesheets -->

    @livewireStyles
    @stack('css')
</head>

<body>
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        <!-- Sidebar -->
        @include('partials.sidebar-left')
        <!-- END Sidebar -->

        <!-- Header -->
        <header id="page-header">
            @include('partials.header')
        </header>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            <div class="content">
                {{ $slot }}
            </div>
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <footer id="page-footer" class="bg-body-light">
            @include('partials.footer')
        </footer>
        <!-- END Footer -->

    </div>
    <!-- END Page Container -->

    @include('partials.scripts')
    @livewireScripts
    @livewire('wire-elements-modal')
    @stack('js')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', ({ message, type }) => {
                $.notify({ message }, {
                    type: type === 'error' ? 'danger' : (type || 'info'),
                    placement: { from: 'top', align: 'right' },
                    delay: 4000,
                    animate: { enter: 'animated fadeInDown', exit: 'animated fadeOutUp' },
                });
            });
        });
    </script>
</body>

</html>
