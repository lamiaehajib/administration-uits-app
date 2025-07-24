<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantInfoo extends Model
{
    use HasFactory;
    protected $table = 'important_infoo';
    protected $fillable = [
        'factures_id',
        'info',
    ];

    /**
     * تعريف العلاقة بين important_info و devis.
     * تتيح لنا استرجاع الـ devis المرتبط بكل "important_info".
     */
    public function facture()
    {
        return $this->belongsTo(facture::class); 
    }
}
