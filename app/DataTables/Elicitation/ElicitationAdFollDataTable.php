<?php

namespace App\DataTables\Elicitation;

use App\Models\ElicitationAdFoll;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ElicitationAdFollDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {        
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('satker', function ($data) {
                return Str::limit(strip_tags($data->satker?->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case?->nama_kasus), 128, '');
            })
            ->editColumn('interviewer_name', function ($data) {
                if ($data->elinterview != null ) {
                    return Str::limit(strip_tags($data->elinterview?->interviewer_name), 128, '');
                }
                return '';
            })
            ->editColumn('saran_dan_tindak_lanjut', function ($data) {
                return Str::limit(strip_tags($data->saran_dan_tindak_lanjut), 128, '');
            })
            ->editColumn('saran_dan_tindak_lanjut_date', function ($data) {
                if ($data->saran_dan_tindak_lanjut_date) {
                    $formattedDate = \Carbon\Carbon::parse($data->saran_dan_tindak_lanjut_date)->isoFormat('DD MMMM YYYY');
                    return $formattedDate;
                }
                return '';
            })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.open.elicitation-advice-and-followup.action', compact('data'))->render();
                } else {
                    return view('backoffice.open.elicitation-advice-and-followup.action-completed', compact('data'))->render();
                }
            })
            ->rawColumns([
                'action'
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ElicitationAdFoll $model): QueryBuilder
    {
        $user = auth()->user();
        $satker = $user->satker;
        $id_satker = $satker->id_satker;
        return $model->newQuery()
            ->with(['elinterview', 'case', 'satker'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function($q) use ($user, $satker, $id_satker) {
                    $q->where('elicitation_saran_dan_tindak_lanjut.satker_id', $id_satker);
                }
            )->orderby('elicitation_saran_dan_tindak_lanjut.created_at','DESC');;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('open.data.elicit-adfoll.index')
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
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('NO')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('NO'),
            Column::make('satker')
                ->name('satker.nama_satker')
                ->title('NAMA SATKER')
                ->className('text-center')
                ->footer('NAMA SATKER'),
            Column::make('kasus')
                ->name('case.nama_kasus')
                ->title('KASUS')
                ->className('text-center')
                ->footer('KASUS'),
            Column::make('interviewer_name')
                ->name('elinterview.interviewer_name')
                ->title('INTERVIEWER')
                ->className('text-center')
                ->footer('INTERVIEWER'),
            Column::make('saran_dan_tindak_lanjut_date')
                ->name('saran_dan_tindak_lanjut_date')
                ->title('TANGGAL TINDAK LANJUT')
                ->className('text-center')
                ->footer('TANGGAL TINDAK LANJUT'),
            Column::make('saran_dan_tindak_lanjut')
                ->name('saran_dan_tindak_lanjut')
                ->title('SARAN TINDAK LANJUT')
                ->className('text-center')
                ->footer('SARAN TINDAK LANJUT'),
            Column::make('action')
                ->title('Aksi')
                ->className('text-center')
                ->footer('Aksi')
                ->width(150)
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'InterogationRecord_' . date('YmdHis');
    }
}
