<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;

class CSVImport implements ToCollection
{
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $importFile = fopen($this->filePath, "r");

        $columns = [
            'Number',
            'Area',
            'XM',
            'YM',
            'Major',
            'Minor',
            'Angle',
            'Slice'
        ];

        return fgetcsv($importFile);
    }
}
