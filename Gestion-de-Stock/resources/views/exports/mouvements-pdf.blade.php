<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export - Mouvements de Stock</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 9px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #5e72e4; }
        .title { font-size: 18px; margin: 10px 0; }
        .info { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 4px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 10px; }
        .price { text-align: right; }
        .center { text-align: center; }
        .entree { color: #2dce89; }
        .sortie { color: #f5365c; }
        .cancelled { color: #f5365c; text-decoration: line-through; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Gestion de Stock</div>
        <div class="title">Mouvements de Stock</div>
        <div class="info">
            Généré le {{ now()->format('d/m/Y à H:i') }}
            @if($scope === 'all')
                <br>Export complet - Toutes les données
            @else
                <br>Export page actuelle
            @endif
            @if(isset($filters) && count($filters) > 0)
                <br>Filtres appliqués: {{ implode(', ', $filters) }}
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Produit</th>
                <th>Réf.</th>
                <th>Qté</th>
                <th>Prix Unit.</th>
                <th>Valeur</th>
                <th>Date Cmd</th>
                <th>Utilisateur</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mouvements as $mouvement)
            @php
                $prixUnitaire = $mouvement->produit ? $mouvement->produit->prix : 0;
                $valeurTotale = $prixUnitaire * $mouvement->quantite;
                $rowClass = $mouvement->canceled ? 'cancelled' : '';
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="center">{{ $mouvement->id }}</td>
                <td class="center {{ $mouvement->type === 'entrée' ? 'entree' : 'sortie' }}">
                    {{ ucfirst($mouvement->type) }}
                </td>
                <td>{{ $mouvement->produit ? $mouvement->produit->nom : 'N/A' }}</td>
                <td>{{ $mouvement->produit ? $mouvement->produit->reference : 'N/A' }}</td>
                <td class="center">{{ $mouvement->quantite }}</td>
                <td class="price">{{ number_format($prixUnitaire, 2) }}</td>
                <td class="price">{{ number_format($valeurTotale, 2) }}</td>
                <td class="center">{{ $mouvement->date_cmd ? $mouvement->date_cmd->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $mouvement->utilisateur ? $mouvement->utilisateur->utilisateur : 'N/A' }}</td>
                <td class="center">{{ $mouvement->canceled ? 'Annulé' : 'Actif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        @php
            $totalMouvements = count($mouvements);
            $totalEntrees = $mouvements->where('type', 'entrée')->where('canceled', false)->count();
            $totalSorties = $mouvements->where('type', 'sortie')->where('canceled', false)->count();
            $valeurTotale = $mouvements->where('canceled', false)->sum(function($m) {
                return $m->quantite * ($m->produit ? $m->produit->prix : 0);
            });
        @endphp
        <p>Total: {{ $totalMouvements }} mouvement(s) | Entrées: {{ $totalEntrees }} | Sorties: {{ $totalSorties }}</p>
        <p>Valeur totale des mouvements: {{ number_format($valeurTotale, 2) }} MAD</p>
        <p>Document généré automatiquement par le système de gestion de stock</p>
    </div>
</body>
</html>
