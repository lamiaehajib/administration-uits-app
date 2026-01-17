<?php

namespace App\Exports;

use App\Models\Charge;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class ChargesExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected $filters;
    protected $rowNumber = 1;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Récupérer les charges selon les filtres
     */
    public function collection()
    {
        $query = Charge::with(['category', 'user']);

        // Appliquer les filtres
        if (isset($this->filters['annee']) && isset($this->filters['mois'])) {
            // Filtrage par mois spécifique
            $query->duMois($this->filters['mois'], $this->filters['annee']);
        } elseif (isset($this->filters['annee'])) {
            // Filtrage par année complète
            $dateDebut = Carbon::create($this->filters['annee'], 1, 1)->startOfYear();
            $dateFin = Carbon::create($this->filters['annee'], 12, 31)->endOfYear();
            $query->entreDates($dateDebut, $dateFin);
        }

        if (isset($this->filters['date_debut']) && isset($this->filters['date_fin'])) {
            $query->entreDates($this->filters['date_debut'], $this->filters['date_fin']);
        }

        if (isset($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        if (isset($this->filters['category_id'])) {
            $query->where('charge_category_id', $this->filters['category_id']);
        }

        if (isset($this->filters['statut'])) {
            $query->where('statut_paiement', $this->filters['statut']);
        }

        if (isset($this->filters['search'])) {
            $query->search($this->filters['search']);
        }

        return $query->orderBy('date_charge', 'desc')->get();
    }

    /**
     * En-têtes des colonnes
     */
    public function headings(): array
    {
        return [
            'N° Référence',
            'Date',
            'Libellé',
            'Catégorie',
            'Type',
            'Montant (DH)',
            'Montant Payé (DH)',
            'Reste à Payer (DH)',
            'Statut',
            'Mode Paiement',
            'Fournisseur',
            'Date Échéance',
            'Récurrent',
            'Fréquence',
            'Créé par',
            'Notes'
        ];
    }

    /**
     * Mapper chaque charge vers une ligne Excel
     */
    public function map($charge): array
    {
        $this->rowNumber++;
        
        return [
            $charge->numero_reference,
            Carbon::parse($charge->date_charge)->format('d/m/Y'),
            $charge->libelle,
            $charge->category?->nom ?? 'Sans catégorie',
            ucfirst($charge->type),
            number_format($charge->montant, 2),
            number_format($charge->montant_paye, 2),
            number_format($charge->montant - $charge->montant_paye, 2),
            $this->getStatutLabel($charge->statut_paiement),
            $this->getModePaiementLabel($charge->mode_paiement),
            $charge->fournisseur ?? '-',
            $charge->date_echeance ? Carbon::parse($charge->date_echeance)->format('d/m/Y') : '-',
            $charge->recurrent ? 'Oui' : 'Non',
            $charge->recurrent ? ucfirst($charge->frequence ?? 'unique') : '-',
            $charge->user?->name ?? '-',
            $charge->notes ?? '-'
        ];
    }

    /**
     * Styles du tableau
     */
    public function styles(Worksheet $sheet)
    {
        // Style de l'en-tête
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1976D2'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Hauteur de l'en-tête
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Bordures pour toutes les cellules
        $lastRow = $this->rowNumber;
        $sheet->getStyle("A1:P{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Centrer les colonnes numériques et dates
        $sheet->getStyle("B2:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Date
        $sheet->getStyle("E2:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Type
        $sheet->getStyle("F2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Montants
        $sheet->getStyle("I2:I{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Statut
        $sheet->getStyle("J2:J{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Mode paiement
        $sheet->getStyle("L2:L{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Date échéance
        $sheet->getStyle("M2:N{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Récurrent/Fréquence

        return [];
    }

    /**
     * Titre de la feuille
     */
    public function title(): string
    {
        if (isset($this->filters['annee']) && isset($this->filters['mois'])) {
            $mois = Carbon::create($this->filters['annee'], $this->filters['mois'])->format('F Y');
            return "Charges {$mois}";
        } elseif (isset($this->filters['annee'])) {
            return "Charges {$this->filters['annee']}";
        }
        
        return 'Charges';
    }

    /**
     * Helpers
     */
    private function getStatutLabel($statut)
    {
        return match($statut) {
            'paye' => 'Payé',
            'impaye' => 'Impayé',
            'partiel' => 'Paiement Partiel',
            default => $statut
        };
    }

    private function getModePaiementLabel($mode)
    {
        return match($mode) {
            'especes' => 'Espèces',
            'virement' => 'Virement',
            'cheque' => 'Chèque',
            'carte' => 'Carte Bancaire',
            'autre' => 'Autre',
            default => $mode
        };
    }
}