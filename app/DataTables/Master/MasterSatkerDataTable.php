<?php

namespace App\DataTables\Master;

use App\Models\MasterSatker;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\LazyDataTablesExportHandler;

class MasterSatkerDataTable extends DataTable
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
            ->editColumn('tipe_satker', function($data) {
                switch((string) $data->tipe_satker) {
                    case "1": return "Kejaksaan Agung";
                    case "2": return "Kejaksaan Tinggi";
                    case "3": return "Kejaksaan Negeri";
                    case "4": return "Cabang Kejaksaan Negeri";
                    default : return "-";
                }
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.master.satker.action', compact('data'))->render();
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MasterSatker $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(MasterSatker $model)
    {
        return $model->newQuery()->orderBy('id_satker');
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
                'url' => route('master.satker.index')
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
                ->title('Kode Satker')
                ->footer('Kode Satker'),

            Column::make('nama_satker')
                ->title('Nama Satker')
                ->footer('Nama Satker'),

            Column::make('tipe_satker')
                ->title('Tipe Satker')
                ->footer('Tipe Satker'),

            Column::make('provinsi')
                ->title('Provinsi')
                ->footer('Provinsi'),

            Column::make('city')
                ->title('Kota')
                ->footer('Kota'),

            Column::make('alamat_satker')
                ->title('Alamat')
                ->footer('Alamat'),

            Column::make('action')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
                ->footer('Aksi')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'MasterSatker_' . date('YmdHis');
    }
}
