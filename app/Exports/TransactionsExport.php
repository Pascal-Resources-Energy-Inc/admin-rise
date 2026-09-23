<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Carbon\Carbon;

class TransactionsExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithMapping, WithCustomValueBinder, WithColumnFormatting
{
    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Quantity',
            'Amount',
            'Dealer',
            'Customer',
            'Dealer Points',
            'Customer Points',
            'Item',
        ];
    }

    public function map($transaction): array
    {
        // Export a genuine Excel date, then lock its display format below.
        // This prevents Excel from applying the computer's regional date format.
        $date = $transaction->date
            ? ExcelDate::dateTimeToExcel(Carbon::createFromFormat('Y-m-d', substr((string) $transaction->date, 0, 10))->startOfDay())
            : null;

        return [
            $transaction->id,
            $date,
            number_format($transaction->qty, 2),
            number_format($transaction->qty * $transaction->price, 2),
            optional($transaction->dealer)->name ?? '',
            optional($transaction->customer)->name ?? '',
            $transaction->points_dealer,
            $transaction->points_client,
            $transaction->item,
        ];
    }

    /**
     * Use a fixed Excel date format rather than General, so every downloaded
     * file displays the Date column as YYYY-MM-DD on any computer.
     */
    public function columnFormats(): array
    {
        return [
            'B' => 'yyyy-mm-dd',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        // The Date heading is also in column B, so only bind actual Excel
        // serial date values as numeric cells.
        if ($cell->getColumn() === 'B' && is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
