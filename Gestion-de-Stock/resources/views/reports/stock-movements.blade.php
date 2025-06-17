<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de Mouvements de Stock</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 20px; }
        .date { text-align: right; margin-bottom: 20px; }
        .filters { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th { background-color: #f2f2f2; padding: 8px; text-align: left; }
        td { padding: 8px; }
        .entree { background-color: #e6ffe6; }
        .sortie { background-color: #ffe6e6; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Rapport de Mouvements de Stock</h1>
    <div class="date">Généré le: {{ $date }}</div>
    
    <div class="filters">
        <p><strong>Période:</strong> Du {{ $startDate }} au {{ $endDate }}</p>
        <p><strong>Type de mouvement:</strong> {{ ucfirst($type) }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Référence</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Utilisateur</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
                <tr class="{{ $movement->type }}">
                    <td>{{ \Carbon\Carbon::parse($movement->date_cmd)->format('d/m/Y H:i') }}</td>
                    <td>{{ $movement->produit->reference }}</td>
                    <td>{{ $movement->produit->nom }}</td>
                    <td>{{ $movement->type }}</td>
                    <td>{{ $movement->quantite }}</td>
                    <td>{{ $movement->utilisateur->name ?? 'N/A' }}</td>
                    <td>{{ $movement->commentaire }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total des entrées:</td>
                <td>{{ $totalEntrees }}</td>
                <td colspan="2"></td>
            </tr>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total des sorties:</td>
                <td>{{ $totalSorties }}</td>
                <td colspan="2"></td>
            </tr>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Balance:</td>
                <td>{{ $totalEntrees - $totalSorties }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    
    <div>
        <p>Ce rapport a été généré automatiquement par le système de gestion de stock.</p>
    </div>
</body>
</html>
