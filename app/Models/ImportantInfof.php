<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantInfof extends Model
{
    use HasFactory;

    protected $table = 'important_infof';
    protected $fillable = [
        'devisf_id',
        'info',
    ];

    public function devisf()
    {
        return $this->belongsTo(Devisf::class, 'devisf_id'); 
    }
    
}
