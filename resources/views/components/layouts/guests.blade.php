<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Connexion - Le Sénévé</title>

    <meta name="description" content="Page de connexion - Le Sénévé">
    <meta name="author" content="Relwindé">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
    <!-- END Stylesheets -->

    @livewireStyles
</head>

<body>
    <div id="page-container">
        <main id="main-container">
            {{ $slot }}
        </main>
    </div>

    <script src="/assets/js/oneui.core.min.js"></script>
    <script src="/assets/js/oneui.app.min.js"></script>
    <script src="/assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="/assets/js/pages/op_auth_signin.min.js"></script>

    @livewireScripts
</body>

</html>
