<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devisf extends Model
{
    use HasFactory;
     // اسم الجدول
protected $primaryKey = 'id'; // المفتاح الأساسي (إذا كان مختلفًا عن 'id')
public $incrementing = true; // إذا كان المفتاح الأساسي عددًا صحيحًا
protected $keyType = 'int'; // نوع المفتاح الأسا
    protected $table = 'devisf';

    protected $fillable = [
        'devis_num', 'date', 'titre', 'client', 'contact', 
        'ref', 'total_ht', 'tva', 'total_ttc', 'important','vide','user_id','currency',
    ];

    // العلاقة بين Devis و DevisItem (One-to-Many)
    public function items()
{
    return $this->hasMany(DevisItemf::class, 'devis_id'); // استخدم 'devis_id' كاسم العمود الصحيح
}

public function Dashboard()
{
    return $this->hasMany(Dashboard::class, 'devisf_id');
}

public function ImportantInfof()
{
    return $this->hasMany(ImportantInfof::class, 'devisf_id');
}

public function user()
    {
        return $this->belongsTo(User::class);
    }

}
