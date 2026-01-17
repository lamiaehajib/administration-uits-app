<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ChargesStatsExport implements 
    FromCollection,
    WithHeadings,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected $stats;

    public function __construct($stats)
    {
        $this->stats = $stats;
    }

    public function collection()
    {
        $data = collect([
            ['STATISTIQUES GÉNÉRALES', ''],
            ['Total des charges payées', number_format($this->stats['total_charges'], 2) . ' DH'],
            ['Total impayé', number_format($this->stats['total_impaye'], 2) . ' DH'],
            ['Nombre total de charges', $this->stats['nombre_charges']],
            ['', ''],
            ['PAR TYPE', ''],
            ['Charges fixes', number_format($this->stats['total_fixe'], 2) . ' DH'],
            ['Charges variables', number_format($this->stats['total_variable'], 2) . ' DH'],
            ['', ''],
            ['PAR STATUT', ''],
            ['Payées', $this->stats['count_paye']],
            ['Impayées', $this->stats['count_impaye']],
            ['Paiement partiel', $this->stats['count_partiel']],
            ['', ''],
            ['TOP 5 CATÉGORIES', 'Montant (DH)'],
        ]);

        // Ajouter les catégories
        foreach ($this->stats['par_categorie'] as $cat) {
            $data->push([
                $cat['nom'] . ' (' . $cat['count'] . ' charge(s))',
                number_format($cat['total'], 2) . ' DH'
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Indicateur', 'Valeur'];
    }

    public function styles(Worksheet $sheet)
    {
        // En-tête
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '388E3C'],
            ],
        ]);

        // Titres de sections (en gras)
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('A10')->getFont()->setBold(true);
        $sheet->getStyle('A15')->getFont()->setBold(true);

        return [];
    }

    public function title(): string
    {
        return 'Statistiques';
    }
}