<?php

namespace App\Imports;

use App\Models\ImportTarget;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TargetsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use Importable;

    public function model(array $row)
    {
        $code = $row['code'];
        $products = Product::where('code', $code)->first();
        $products_id = $products ? $products->id : null;

        return new ImportTarget([
            'year' => $row['tahun'],
            'month' => $row['bulan'],
            'products_id' => $products_id,
            'qty' => $row['qty'],
        ]);
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}