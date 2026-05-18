<?php

namespace App\DataTables\Interogation;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\InterogationRecord;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class InterogationRecordDataTable extends DataTable
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
            ->editColumn('nama_satker', function ($data) {
                if ($data->case) {
                    return Str::limit($data->satker?->nama_satker, 128, '');
                }
                return '';
            })
            ->editColumn('kasus', function ($data) {
                if ($data->case) {
                    return Str::limit(strip_tags($data->case?->nama_kasus), 128, '');
                }
                return '';
            })
            ->editColumn('download_bap', function ($data) {
                $link = route('open.data.record.download-bap', encrypt($data->id_interogation_record));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.interogation-record.action', compact('data'))->render();
            })
            ->rawColumns([
                'download_bap',
                'action'
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(InterogationRecord $model): QueryBuilder
    {
        $user = auth()->user();
        $idSatker = $user->id_satker;
        return $model->newQuery()->join('case_progresses','interrogation_berita_acara.case_id','case_progresses.case_id')
        ->with(['case', 'satker'])
        ->when(
            !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
            function ($q) use ($idSatker) {
                $q->where(function ($query) use ($idSatker) {
                    $query->whereHas('case', function ($caseQuery) use ($idSatker) {
                        $caseQuery->where('id_satker', $idSatker);
                    })
                    ->orWhereHas('satker', function ($satkerQuery) use ($idSatker) {
                        $satkerQuery->where('parent_id', $idSatker);
                    });
                });
            }
        )
        ->orderby('interrogation_berita_acara.created_at','DESC');;
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
                'url' => route('open.data.interrog-record.index')
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
            Column::make('nama_satker')
                ->name('satker.nama_satker')
                ->title('NAMA SATKER')
                ->className('text-center')
                ->footer('NAMA SATKER'),
            Column::make('letter_number')
                ->name('letter_number')
                ->title('NOMOR SURAT')
                ->className('text-center')
                ->footer('NOMOR SURAT'),
            Column::make('letter_date')
                ->name('letter_date')
                ->title('TANGGAL SURAT')
                ->className('text-center')
                ->footer('TANGGAL SURAT'),
            Column::make('kasus')
                ->name('case.nama_kasus')
                ->title('NAMA KASUS')
                ->className('text-center')
                ->footer('NAMA KASUS'),
            Column::make('perihal')
                ->name('perihal')
                ->title('PERIHAL')
                ->className('text-center')
                ->footer('PERIHAL'),
            Column::make('target_name')
                ->name('target_name')
                ->title('NAMA TARGET')
                ->className('text-center')
                ->footer('NAMA TARGET'),

            Column::make('download_bap')
                ->name('download_bap')
                ->title('UNDUH BAP (IN.10)')
                ->className('text-center')
                ->footer('UNDUH BAP (IN.10)'),

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
