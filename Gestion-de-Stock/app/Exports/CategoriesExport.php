<?php

namespace App\Exports;

use App\Models\Categorie;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoriesExport implements FromQuery, WithHeadings, WithMapping, WithStyles
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
        $query = Categorie::withCount('produits');

        // Apply search filter
        if ($this->request->has('search') && !empty($this->request->search)) {
            $query->where('nom', 'like', '%' . $this->request->search . '%');
        }

        // Apply sorting
        $sort = $this->request->get('sort', 'nom_asc');
        switch ($sort) {
            case 'nom_desc':
                $query->orderBy('nom', 'desc');
                break;
            case 'produits_count':
                $query->orderBy('produits_count', 'desc');
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
            'Nombre de Produits',
            'Date de Création',
            'Dernière Modification'
        ];
    }

    public function map($categorie): array
    {
        return [
            $categorie->id,
            $categorie->nom,
            $categorie->produits_count,
            $categorie->created_at->format('d/m/Y H:i'),
            $categorie->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
