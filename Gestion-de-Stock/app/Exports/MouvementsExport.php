<?php

namespace App\Exports;

use App\Models\MouvementStock;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MouvementsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $request;
    protected $allData;

    public function __construct($request, $allData = false)
    {
        $this->request = $request;
        $this->allData = $allData;
    }

    public function query()
    {
        $query = MouvementStock::with(['produit', 'utilisateur']);

        // Apply search filter
        if ($this->request->has('search') && !empty($this->request->search)) {
            $query->where(function($q) {
                $q->whereHas('produit', function($subQ) {
                    $subQ->where('nom', 'like', '%' . $this->request->search . '%')
                         ->orWhere('reference', 'like', '%' . $this->request->search . '%');
                })
                ->orWhereHas('utilisateur', function($subQ) {
                    $subQ->where('utilisateur', 'like', '%' . $this->request->search . '%');
                });
            });
        }

        // Apply type filter
        if ($this->request->has('type') && !empty($this->request->type)) {
            $query->where('type', $this->request->type);
        }

        // Apply date range filter
        if ($this->request->has('date_debut') && !empty($this->request->date_debut)) {
            $query->whereDate('date_cmd', '>=', $this->request->date_debut);
        }
        if ($this->request->has('date_fin') && !empty($this->request->date_fin)) {
            $query->whereDate('date_cmd', '<=', $this->request->date_fin);
        }

        // Apply sorting
        $sort = $this->request->get('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('date_cmd', 'asc');
                break;
            case 'produit':
                $query->join('produits', 'mouvement_stocks.produit_id', '=', 'produits.id')
                      ->orderBy('produits.nom', 'asc');
                break;
            case 'type':
                $query->orderBy('type', 'asc');
                break;
            case 'quantite_asc':
                $query->orderBy('quantite', 'asc');
                break;
            case 'quantite_desc':
                $query->orderBy('quantite', 'desc');
                break;
            default:
                $query->orderBy('date_cmd', 'desc');
                break;
        }

        // If not exporting all data, apply pagination limit
        if (!$this->allData) {
            $query->limit(10);
            if ($this->request->has('page')) {
                $offset = ($this->request->page - 1) * 10;
                $query->offset($offset);
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Type',
            'Produit',
            'Référence',
            'Quantité',
            'Prix Unitaire (MAD)',
            'Valeur Totale (MAD)',
            'Date Commande',
            'Date Réception',
            'Utilisateur',
            'Statut'
        ];
    }

    public function map($mouvement): array
    {
        $prixUnitaire = $mouvement->produit ? $mouvement->produit->prix : 0;
        $valeurTotale = $prixUnitaire * $mouvement->quantite;

        return [
            $mouvement->id,
            ucfirst($mouvement->type),
            $mouvement->produit ? $mouvement->produit->nom : 'N/A',
            $mouvement->produit ? $mouvement->produit->reference : 'N/A',
            $mouvement->quantite,
            number_format($prixUnitaire, 2) . ' MAD',
            number_format($valeurTotale, 2) . ' MAD',
            $mouvement->date_cmd ? $mouvement->date_cmd->format('d/m/Y') : 'N/A',
            $mouvement->date_reception ? $mouvement->date_reception->format('d/m/Y') : 'En attente',
            $mouvement->utilisateur ? $mouvement->utilisateur->utilisateur : 'N/A',
            $mouvement->canceled ? 'Annulé' : 'Actif'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
