<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    protected $table = '_dashboard';

    protected $fillable = [
        'factures_id',
        'devis_id',
        'reussitef_id',
        'reussite_id',
        'attestation_allinone_id',
        'attestation_formation_id',
        'attestation_id',
        'devisf_id',
    ];

    // العلاقات مع النماذج الأخرى
    public function facture()
    {
        return $this->belongsTo(Facture::class, 'factures_id');
    }

    public function devis()
    {
        return $this->belongsTo(Devis::class, 'devis_id');
    }

    public function devisf()
    {
        return $this->belongsTo(Devisf::class, 'devisf_id');
    }

    public function reussitef()
    {
        return $this->belongsTo(Reussitef::class, 'reussitef_id');
    }

    public function reussite()
    {
        return $this->belongsTo(Reussite::class, 'reussite_id');
    }

    public function attestationAllinone()
    {
        return $this->belongsTo(AttestationAllinone::class, 'attestation_allinone_id');
    }

    public function attestationFormation()
    {
        return $this->belongsTo(AttestationFormation::class, 'attestation_formation_id');
    }

    public function attestation()
    {
        return $this->belongsTo(Attestation::class, 'attestation_id');
    }
}

