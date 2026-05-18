<?php

namespace App\DataTables\Master;

use App\Models\MasterPegawai;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\LazyDataTablesExportHandler;

class MasterPegawaiDataTable extends DataTable
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
                return view('backoffice.master.pegawai.action', compact('data'))->render();
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MasterPegawai $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(MasterPegawai $model)
    {
        return $model->newQuery()
                ->when(!auth()->user()->hasRole('superadmin'), function($q){
                    return $q->where('id_satker', auth()->user()->satker->id_satker);
                })
                ->join('master_satker', 'master_satker.id_satker', 'master_pegawai.id_satker')
                ->orderBy('master_satker.nama_satker')
                ->orderBy('master_pegawai.nama');
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
                'url' => route('master.pegawai.index')
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
            
            Column::make('nama_satker')
                ->name('master_satker.nama_satker')
                ->title('Nama Satker')
                ->footer('Nama Satker'),

            Column::make('nip')
                ->title('NIP')
                ->footer('NIP'),

            Column::make('nama')
                ->title('Nama')
                ->footer('Nama'),

            Column::make('action')
                ->title('Aksi')
                ->width('150px')
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
        return 'MasterPegawai_' . date('YmdHis');
    }
}
