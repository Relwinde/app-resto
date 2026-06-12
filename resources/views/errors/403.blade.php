<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Sénévé — Accès refusé</title>
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
</head>
<body class="bg-body-light">
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:2rem;">
        <div class="text-center" style="max-width:480px;">
            <div class="mb-4">
                <i class="fa fa-shield-alt" style="font-size:4rem; color:#f0ad4e;"></i>
            </div>
            <h1 class="font-w700" style="font-size:4rem; line-height:1;">403</h1>
            <h3 class="mb-2">Accès non autorisé</h3>
            <p class="text-muted mb-4">
                Vous ne disposez pas des permissions nécessaires pour accéder à cette page.<br>
                Contactez votre administrateur si vous pensez qu'il s'agit d'une erreur.
            </p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                <i class="fa fa-fw fa-home mr-1"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>
