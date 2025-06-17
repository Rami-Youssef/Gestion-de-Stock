<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport d'Inventaire de Stock</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 20px; }
        .date { text-align: right; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th { background-color: #f2f2f2; padding: 8px; text-align: left; }
        td { padding: 8px; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Rapport d'Inventaire de Stock</h1>
    <div class="date">Date: {{ $date }}</div>
    
    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Nom du produit</th>
                <th>Catégorie</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Valeur totale</th>
            </tr>
        </thead>
        <tbody>
            @php $currentCategory = null; @endphp
            @foreach($products as $product)
                @if($currentCategory !== $product->categorie->nom)
                    <tr>
                        <td colspan="6" style="background-color: #f2f2f2; font-weight: bold;">{{ $product->categorie->nom }}</td>
                    </tr>
                    @php $currentCategory = $product->categorie->nom; @endphp
                @endif
                <tr>
                    <td>{{ $product->reference }}</td>
                    <td>{{ $product->nom }}</td>
                    <td>{{ $product->categorie->nom }}</td>
                    <td>{{ $product->quantite }}</td>
                    <td>{{ number_format($product->prix, 2) }} MAD</td>
                    <td>{{ number_format($product->quantite * $product->prix, 2) }} MAD</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">Valeur totale de l'inventaire:</td>
                <td>{{ number_format($totalValue, 2) }} MAD</td>
            </tr>
        </tfoot>
    </table>
    
    <div>
        <p>Ce rapport a été généré automatiquement par le système de gestion de stock.</p>
    </div>
</body>
</html>
