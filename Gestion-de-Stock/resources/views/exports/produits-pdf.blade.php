<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export - Produits</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #5e72e4; }
        .title { font-size: 18px; margin: 10px 0; }
        .info { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 10px; }
        .price { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Gestion de Stock</div>
        <div class="title">Liste des Produits</div>
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
                <th>Nom</th>
                <th>Référence</th>
                <th>Catégorie</th>
                <th>Prix (MAD)</th>
                <th>Stock</th>
                <th>Création</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
            <tr>
                <td class="center">{{ $produit->id }}</td>
                <td>{{ $produit->nom }}</td>
                <td>{{ $produit->reference }}</td>
                <td>{{ $produit->categorie ? $produit->categorie->nom : 'N/A' }}</td>
                <td class="price">{{ number_format($produit->prix, 2) }} MAD</td>
                <td class="center">{{ $produit->quantite }}</td>
                <td class="center">{{ $produit->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ count($produits) }} produit(s)</p>
        <p>Valeur totale du stock: {{ number_format($produits->sum(function($p) { return $p->prix * $p->quantite; }), 2) }} MAD</p>
        <p>Document généré automatiquement par le système de gestion de stock</p>
    </div>
</body>
</html>
