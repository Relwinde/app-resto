<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu {{ $commande->numero }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 24px 0;
        }

        .receipt {
            background: #fff;
            width: 80mm;
            padding: 10mm 6mm;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }

        .restaurant-name {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .receipt-subtitle {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-bottom: 8px;
        }

        .status-badge {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            padding: 4px 0;
            margin-bottom: 4px;
        }

        .status-paye    { color: #2d7d46; }
        .status-nonpaye { color: #c0392b; border: 1px dashed #c0392b; }
        .status-annule  { color: #888; border: 1px dashed #888; }

        .divider {
            border: none;
            border-top: 1px dashed #333;
            margin: 6px 0;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .meta-row .label { color: #555; }

        table { width: 100%; border-collapse: collapse; margin-top: 4px; }

        thead th {
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #333;
            padding: 2px 0;
        }

        thead th:first-child { text-align: left; }
        thead th:not(:first-child) { text-align: right; }

        tbody td { font-size: 11px; padding: 3px 0; vertical-align: top; }
        tbody td:first-child { text-align: left; }
        tbody td:not(:first-child) { text-align: right; }

        tfoot td {
            font-size: 13px;
            font-weight: bold;
            padding-top: 4px;
            border-top: 1px solid #333;
        }
        tfoot td:last-child { text-align: right; }

        .receipt-footer {
            text-align: center;
            font-size: 10px;
            color: #888;
            margin-top: 10px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
                display: block;
            }
            .receipt {
                box-shadow: none;
                width: 100%;
                padding: 2mm 2mm;
            }
        }
    </style>
</head>
<body>
<div class="receipt">

    <div class="restaurant-name">{{ config('app.name') }}</div>
    <div class="receipt-subtitle">REÇU DE CAISSE</div>

    @php
        $statutClass = match($commande->statut) {
            'payee'   => 'status-paye',
            'annulee' => 'status-annule',
            default   => 'status-nonpaye',
        };
        $statutLabel = match($commande->statut) {
            'payee'          => '✓ PAYÉ',
            'annulee'        => '✗ ANNULÉ',
            'en_attente'     => '⚠ NON PAYÉ',
            'en_preparation' => '⚠ NON PAYÉ',
            'servie'         => '⚠ NON PAYÉ',
            default          => '⚠ NON PAYÉ',
        };
    @endphp

    <div class="status-badge {{ $statutClass }}">{{ $statutLabel }}</div>

    <hr class="divider">

    <div class="meta-row">
        <span class="label">Commande</span>
        <span>{{ $commande->numero }}</span>
    </div>
    <div class="meta-row">
        <span class="label">Date</span>
        <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="meta-row">
        <span class="label">Caissier</span>
        <span>{{ $commande->user->name }}</span>
    </div>
    @if ($commande->table_numero || $commande->client_nom)
    <div class="meta-row">
        <span class="label">{{ $commande->table_numero ? 'Table' : 'Client' }}</span>
        <span>
            {{ $commande->table_numero ? 'Table ' . $commande->table_numero : '' }}{{ $commande->client_nom ?? '' }}
        </span>
    </div>
    @endif

    <hr class="divider">

    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Qté</th>
                <th>P.U.</th>
                <th>S/T</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commande->items as $item)
            <tr>
                <td>{{ $item->produit?->name ?? '—' }}</td>
                <td>{{ (int) $item->quantite }}</td>
                <td>{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                <td>{{ number_format($item->sous_total, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">TOTAL</td>
                <td>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</td>
            </tr>
        </tfoot>
    </table>

    @if ($commande->mouvement)
    <hr class="divider">

    <div class="meta-row">
        <span class="label">Mode de paiement</span>
        <span>{{ $commande->mouvement->mode_paiement === 'especes' ? 'Espèces' : 'Mobile Money' }}</span>
    </div>

    @if ($commande->mouvement->mode_paiement === 'especes' && $commande->mouvement->montant_recu)
    <div class="meta-row">
        <span class="label">Montant reçu</span>
        <span>{{ number_format($commande->mouvement->montant_recu, 0, ',', ' ') }} FCFA</span>
    </div>
    <div class="meta-row">
        <span class="label">Monnaie rendue</span>
        <span>{{ number_format($commande->mouvement->monnaie_rendue, 0, ',', ' ') }} FCFA</span>
    </div>
    @endif

    @if ($commande->mouvement->reference_mobile)
    <div class="meta-row">
        <span class="label">Réf. transaction</span>
        <span>{{ $commande->mouvement->reference_mobile }}</span>
    </div>
    @endif

    <div class="meta-row">
        <span class="label">Date paiement</span>
        <span>{{ $commande->mouvement->created_at->format('d/m/Y H:i') }}</span>
    </div>
    @endif

    <hr class="divider">

    <div class="receipt-footer">
        Merci de votre visite !<br>
        Imprimé le {{ now()->format('d/m/Y H:i') }}
    </div>

</div>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        window.print();
        window.onafterprint = function () {
            window.close();
        };
    });
</script>
</body>
</html>
