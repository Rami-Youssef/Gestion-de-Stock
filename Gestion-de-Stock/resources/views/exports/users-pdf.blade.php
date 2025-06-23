<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export - Utilisateurs</title>
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
        .center { text-align: center; }
        .admin { color: #f5365c; font-weight: bold; }
        .user { color: #2dce89; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Gestion de Stock</div>
        <div class="title">Liste des Utilisateurs</div>
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
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date de Création</th>
                <th>Dernière Modification</th>
            </tr>
        </thead>
        <tbody>
            @foreach($utilisateurs as $utilisateur)
            <tr>
                <td class="center">{{ $utilisateur->id }}</td>
                <td>{{ $utilisateur->utilisateur }}</td>
                <td>{{ $utilisateur->email }}</td>
                <td class="center {{ $utilisateur->role === 'admin' ? 'admin' : 'user' }}">
                    {{ ucfirst($utilisateur->role) }}
                </td>
                <td class="center">{{ $utilisateur->created_at->format('d/m/Y H:i') }}</td>
                <td class="center">{{ $utilisateur->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        @php
            $totalUsers = count($utilisateurs);
            $admins = $utilisateurs->where('role', 'admin')->count();
            $users = $utilisateurs->where('role', 'user')->count();
        @endphp
        <p>Total: {{ $totalUsers }} utilisateur(s) | Administrateurs: {{ $admins }} | Utilisateurs: {{ $users }}</p>
        <p>Document généré automatiquement par le système de gestion de stock</p>
    </div>
</body>
</html>
