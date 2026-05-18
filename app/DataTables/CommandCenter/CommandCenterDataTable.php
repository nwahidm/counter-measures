<?php

namespace App\DataTables\CommandCenter;

use App\Models\CommandCenter\CommandCenterOBD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CommandCenterDataTable extends DataTable
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
            ->editColumn('obd_name', function ($data) {
                // Remove or comment out the dd() function
                // dd($data);
                return Str::limit(strip_tags($data->obd_name), 128, '');
            })
            ->editColumn('obd_time', function ($data) {
                // Remove or comment out the dd() function
                // dd($data);
                return Str::limit(strip_tags($data->obd_time), 128, '');
            })
            ->editColumn('latitude', function ($data) {
                return Str::limit(strip_tags($data->latitude), 128, '');
            })
            ->editColumn('longitude', function ($data) {
                return Str::limit(strip_tags($data->longitude), 128, '');
            })
            ->editColumn('altitude', function ($data) {
                return Str::limit(strip_tags($data->altitude), 128, '');
            })
            ->editColumn('speed', function ($data) {
                return Str::limit(strip_tags($data->speed), 128, '');
            })->editColumn('total_distance', function ($data) {
                return Str::limit(strip_tags($data->total_distance), 128, '');
            });

            
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CommandCenter\CommandCenterOBD $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CommandCenterOBD $model)
    {
        return $model->newQuery()->orderBy('created_at', 'desc')->limit(100);
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
                'url' => route('commandcenter')
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
                ->title('No')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('No'),
            Column::make('obd_name')
                ->name('obd_name')
                ->title('Device')
                ->footer('Device'),
            Column::make('obd_time')
                ->name('obd_time')
                ->title('Time')
                ->footer('Time'),
            Column::make('latitude')
                ->name('latitude')
                ->title('Latitude')
                ->footer('Latitude'),
            Column::make('longitude')
                ->name('longitude')
                ->title('Longitude')
                ->footer('Longitude'),

            Column::make('altitude')
                ->name('altitude')
                ->title('Altitude')
                ->footer('Altitude'),

            Column::make('speed')
                ->name('speed')
                ->title('Speed')
                ->footer('Speed'),
            Column::make('total_distance')
                ->name('total_distance')
                ->title('Total Jarak')
                ->footer('Total Jarak'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CloseCase_' . date('YmdHis');
    }
}
