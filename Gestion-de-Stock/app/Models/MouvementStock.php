<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;
    
    protected $fillable = ['type', 'quantite', 'date_cmd', 'date_reception', 'produit_id', 'utilisateur_id', 'canceled'];
    
    protected $casts = [
        'date_cmd' => 'datetime',
        'date_reception' => 'datetime',
        'canceled' => 'boolean',
    ];
    
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
    
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
