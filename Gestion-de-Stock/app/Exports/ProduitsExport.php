<?php

namespace App\Exports;

use App\Models\Produit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProduitsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
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
        $query = Produit::with('categorie');

        // Apply search filter
        if ($this->request->has('search') && !empty($this->request->search)) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->request->search . '%')
                  ->orWhere('reference', 'like', '%' . $this->request->search . '%')
                  ->orWhereHas('categorie', function($subQ) {
                      $subQ->where('nom', 'like', '%' . $this->request->search . '%');
                  });
            });
        }

        // Apply category filter
        if ($this->request->has('categorie') && !empty($this->request->categorie)) {
            $query->where('categorie_id', $this->request->categorie);
        }

        // Apply stock filter
        if ($this->request->has('stock') && !empty($this->request->stock)) {
            switch ($this->request->stock) {
                case 'low':
                    $query->where('quantite', '<', 10);
                    break;
                case 'medium':
                    $query->whereBetween('quantite', [10, 50]);
                    break;
                case 'high':
                    $query->where('quantite', '>', 50);
                    break;
            }
        }

        // Apply sorting
        $sort = $this->request->get('sort', 'nom_asc');
        switch ($sort) {
            case 'nom_desc':
                $query->orderBy('nom', 'desc');
                break;
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'quantite_asc':
                $query->orderBy('quantite', 'asc');
                break;
            case 'quantite_desc':
                $query->orderBy('quantite', 'desc');
                break;
            case 'categorie':
                $query->join('categories', 'produits.categorie_id', '=', 'categories.id')
                      ->orderBy('categories.nom', 'asc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('nom', 'asc');
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
            'Nom',
            'Référence',
            'Catégorie',
            'Prix (MAD)',
            'Quantité en Stock',
            'Date de Création',
            'Dernière Modification'
        ];
    }

    public function map($produit): array
    {
        return [
            $produit->id,
            $produit->nom,
            $produit->reference,
            $produit->categorie ? $produit->categorie->nom : 'N/A',
            number_format($produit->prix, 2) . ' MAD',
            $produit->quantite,
            $produit->created_at->format('d/m/Y H:i'),
            $produit->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
