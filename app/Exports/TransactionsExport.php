<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class TransactionsExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithMapping, WithCustomValueBinder
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
        return [
            $transaction->id,
            date('M d, Y', strtotime($transaction->date)),
            number_format($transaction->qty, 2),
            number_format($transaction->qty * $transaction->price, 2),
            optional($transaction->dealer)->name ?? '',
            optional($transaction->customer)->name ?? '',
            $transaction->points_dealer,
            $transaction->points_client,
            $transaction->item,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        // Excel must receive the Date column as text so it cannot change its format or value.
        if ($cell->getColumn() === 'B') {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
