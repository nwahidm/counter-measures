<?php

namespace App\DataTables\Elicitation;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\ElicitationInterview;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ElicitationInterviewDataTable extends DataTable
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
                return Str::limit(strip_tags($data->satker->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                if ($data->case) {
                    return Str::limit(strip_tags($data->case->nama_kasus), 128, '');
                }
                return '';
            })
            ->editColumn('interviewer_schedule', function ($data) {
                if ($data->interviewer_schedule) {
                    $formattedDate = \Carbon\Carbon::parse($data->interviewer_schedule)->isoFormat('DD MMMM YYYY');
                    return $formattedDate;
                }
                return '';
            })
            ->editColumn('analytics_document_status', function ($data) {
                return optional($data->documents)->doc_status_remark ?? '';
            })
            ->editColumn('analytics_video_status', function ($data) {
                return optional($data->videoDocuments)->doc_status_remark ?? '';
            })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.open.elicitation-interview.action', compact('data'))->render();
                } else {
                    return view('backoffice.open.elicitation-interview.action-completed', compact('data'))->render();
                }
            })
            ->rawColumns([
                'action'
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ElicitationInterview $model): QueryBuilder
    {
        $user = auth()->user();
        $satker = $user->satker;
        $id_satker = $satker->id_satker;
        return $model->newQuery()
            ->with(['case', 'satker', 'documents', 'videoDocuments'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function($q) use ($user, $satker, $id_satker) {
                    $q->where('elicitation_hasil_wawancara.satker_id', 'like', "$id_satker%");
                }
            )->orderby('elicitation_hasil_wawancara.created_at','DESC');;
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
                'url' => route('open.data.elicit-interview.index')
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
                ->name('interviewer_name')
                ->title('NAMA INTERVIEWER')
                ->className('text-center')
                ->footer('NAMA INTERVIEWER'),
            Column::make('interviewer_schedule')
                ->name('interviewer_schedule')
                ->title('JADWAL INTERVIEWER')
                ->className('text-center')
                ->footer('JADWAL INTERVIEWER'),

                Column::make('analytics_document_status')
                ->name('analytics_document_status')
                ->title('ANALISA DOKUMEN')
                ->className('text-center')
                ->footer('ANALISA DOKUMEN')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),
                
            Column::make('analytics_video_status')
                ->title('ANALISA VIDEO')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
                ->footer('ANALISA VIDEO'),

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
        return 'ElicitationInterview_' . date('YmdHis');
    }
}
