<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export - Catégories</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #5e72e4; }
        .title { font-size: 18px; margin: 10px 0; }
        .info { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Gestion de Stock</div>
        <div class="title">Liste des Catégories</div>
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
                <th>Nombre de Produits</th>
                <th>Date de Création</th>
                <th>Dernière Modification</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $categorie)
            <tr>
                <td>{{ $categorie->id }}</td>
                <td>{{ $categorie->nom }}</td>
                <td>{{ $categorie->produits_count ?? $categorie->produits->count() }}</td>
                <td>{{ $categorie->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $categorie->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ count($categories) }} catégorie(s)</p>
        <p>Document généré automatiquement par le système de gestion de stock</p>
    </div>
</body>
</html>
