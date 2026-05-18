<?php

namespace App\DataTables\BodyCamDevice;

use Illuminate\Support\Facades\DB;  // Import the DB facade
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\BodyCamDevice\BodyCamDevice;

class BodyCamDeviceDataTable extends DataTable
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
            ->editColumn('device_name', function ($data) {
                return Str::limit(strip_tags($data->device_name), 128, '');
            })
            ->editColumn('satker', function ($data) {
                return Str::limit(strip_tags($data->nama_satker), 128, '');
            })
            ->editColumn('device_source_url', function ($data) {
                return Str::limit(strip_tags($data->device_source_url), 128, '');
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.bodycam.action', compact('data'))->render();
            })
            ->rawColumns([
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Delineation\DelineationInformationVerification $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BodyCamDevice $model)
    {

        return $model->newQuery()
            ->select([
                'bodycam_devices.*', // Include all columns from delineation_information_verification
                'master_satker.nama_satker', // Example of including specific columns from joined tables
                
            ])
            ->join('master_satker', DB::raw("CAST(bodycam_devices.device_used_for AS varchar)"), '=', DB::raw("CAST(master_satker.kode_satker AS varchar)"));
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
                'url' => route('bodycam.body-cam.index')
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
                                        column.search($(this).val(), false, false, true).draw();
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
                ->title('NO ')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('NO'),

            Column::make('device_name')
                ->name('device_name')
                ->title('NAMA ALAT')
                ->className('text-left')
                ->footer('NAMA ALAT'),


            Column::make('satker')
                ->name('satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            
            Column::make('device_source_url')
                ->name('device_source_url')
                ->title('URL KAMERA')
                ->className('text-left')
                ->footer('URL KAMERA'),

            Column::make('action')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
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
        return 'bodycam_' . date('YmdHis');
    }
}
