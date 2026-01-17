<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ChargesMultiSheetExport implements WithMultipleSheets
{
    protected $filters;
    protected $stats;

    public function __construct($filters, $stats)
    {
        $this->filters = $filters;
        $this->stats = $stats;
    }

    public function sheets(): array
    {
        return [
            new ChargesExport($this->filters),
            new ChargesStatsExport($this->stats),
        ];
    }
}