<?php

namespace App\DataTables\Master;

use App\Models\WilayahSatker;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\LazyDataTablesExportHandler;

class MasterWilayahSatkerDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('action', function ($data) {
                return view('backoffice.master.wilayah-satker.action', compact('data'))->render();
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\WilayahSatker $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(WilayahSatker $model)
    {
        return $model->newQuery()
                        ->join('master_satker', 'master_satker.id_satker', '=', 'wilayah_satker.id_satker')
                        ->join('master_wilayah', 'master_wilayah.id_wilayah', '=', 'wilayah_satker.id_wilayah')
                        ->select('wilayah_satker.id', 'master_satker.nama_satker', 'master_satker.kode_satker', 'master_wilayah.nama as nama_wilayah', 'master_wilayah.level as level_wilayah', 'master_wilayah.kode as kode_wilayah');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('master.wilayah-satker.index')
            ])
            ->buttons(
                Button::make('excel')->className('btn-light btn-sm'),
                Button::make('reset')->className('btn-light btn-sm')
            )
            ->dom($domOption)
            ->parameters([
                'initComplete' => "function () {
                                var r = $('#data-table tfoot tr');
                                $('#data-table thead').append(r);
                                this.api().columns().every(function () {
                                    var column = this;
                                    var input = document.createElement('input');
                                    input.className = 'form-control form-control-sm';
                                    $(input).appendTo($(column.footer()).empty())
                                            .on('change', function () {
                                        column.search($(this).val(), false, false,true).draw();
                                                    });
                                });
                            }"
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')
                ->title('No')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('No'),

            Column::make('kode_satker')
                ->name('master_satker.kode_satker')
                ->title('Kode Satker')
                ->footer('Kode Satker'),

            Column::make('nama_satker')
                ->name('master_satker.nama_satker')
                ->title('Nama Satker')
                ->footer('Nama Satker'),

            Column::make('kode_wilayah')
                ->name('master_wilayah.kode')
                ->title('Kode Wilayah')
                ->footer('Kode Wilayah'),

            Column::make('nama_wilayah')
                ->name('master_wilayah.nama')
                ->title('Nama Wilayah')
                ->footer('Nama Wilayah'),

            Column::make('level_wilayah')
                ->name('master_wilayah.level')
                ->title('Level Wilayah')
                ->footer('Level Wilayah'),

            Column::make('action')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->footer('Aksi'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'MasterWilayahSatker_' . date('YmdHis');
    }
}
