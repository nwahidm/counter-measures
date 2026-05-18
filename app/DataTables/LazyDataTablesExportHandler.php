<?php

namespace App\DataTables;

use Illuminate\Support\LazyCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LazyDataTablesExportHandler implements FromCollection, WithHeadings
{
    use Exportable;

    /**
     * @var LazyCollection
     */
    protected $collection;

    /**
     * LazyDataTablesExportHandler constructor.
     *
     * @param LazyCollection $collection
     */
    public function __construct(LazyCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return LazyCollection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $first = $this->collection->first();
        if ($first) {
            return array_keys($first->toArray());
        }

        return [];
    }
}