<?php

namespace App\Exports;

use App\Models\Utilisateur;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithStyles
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
        $query = Utilisateur::query();

        // Apply search filter
        if ($this->request->has('search') && !empty($this->request->search)) {
            $query->where(function($q) {
                $q->where('utilisateur', 'like', '%' . $this->request->search . '%')
                  ->orWhere('email', 'like', '%' . $this->request->search . '%');
            });
        }

        // Apply role filter
        if ($this->request->has('role') && !empty($this->request->role)) {
            $query->where('role', $this->request->role);
        }

        // Apply sorting
        $sort = $this->request->get('sort', 'nom_asc');
        switch ($sort) {
            case 'nom_desc':
                $query->orderBy('utilisateur', 'desc');
                break;
            case 'email_asc':
                $query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $query->orderBy('email', 'desc');
                break;
            case 'role':
                $query->orderBy('role', 'asc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('utilisateur', 'asc');
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
            'Nom d\'utilisateur',
            'Email',
            'Rôle',
            'Date de Création',
            'Dernière Modification'
        ];
    }

    public function map($utilisateur): array
    {
        return [
            $utilisateur->id,
            $utilisateur->utilisateur,
            $utilisateur->email,
            ucfirst($utilisateur->role),
            $utilisateur->created_at->format('d/m/Y H:i'),
            $utilisateur->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
