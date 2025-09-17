<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devis extends Model
{
    use HasFactory , SoftDeletes;



    protected $fillable = [
        'devis_num', 'date', 'titre', 'client', 'contact', 
        'ref', 'total_ht', 'tva', 'total_ttc', 'important','user_id','currency',
    ];

    // العلاقة بين Devis و DevisItem (One-to-Many)
    public function items()
    {
        return $this->hasMany(DevisItem::class);
    }

    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'devis_id');
    }

    public function importantInfos()
{
    return $this->hasMany(ImportantInfo::class); 
}

public function user()
    {
        return $this->belongsTo(User::class);
    }

}

