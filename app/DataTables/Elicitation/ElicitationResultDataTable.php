<?php

namespace App\DataTables\Elicitation;

use App\Models\ElicitationResult;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\InterogationRecord;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ElicitationResultDataTable extends DataTable
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
                return Str::limit(strip_tags($data->case->nama_kasus), 128, '');
            })
            ->editColumn('pelaksanaan_kegiatan', function ($data) {
                return Str::limit(strip_tags($data->pelaksanaan_kegiatan), 128, '');
            })
            ->editColumn('download_report_hasil_pelaksanaan_tugas', function ($data) {
                $link = route('open.elicitation.report.download-hasil-pelaksanaan-tugas',$data->id_elicitation_result);
                $elicitationResult = ElicitationResult::where('id_elicitation_result', $data->id_elicitation_result)->first();
                if($elicitationResult){
                    return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
                }
                return '<h6>Belum Mengisi Hasil Capaian</h6>';
                
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.elicitation-result.action', compact('data'))->render();
            })
            ->rawColumns([
                'download_report_hasil_pelaksanaan_tugas',
                'action'
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ElicitationResult $model): QueryBuilder
    {
        $user = auth()->user();
        $satker = $user->satker;
        $id_satker = $satker->id_satker;
        return $model->newQuery()
            ->with(['elinadfoll', 'elinterview', 'case', 'satker'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function($q) use ($user, $satker, $id_satker) {
                    $q->where('elicitation_hasil_yang_dicapai.satker_id', 'like', "$id_satker%");
                }
            )->join('case_progresses','elicitation_hasil_yang_dicapai.case_id','case_progresses.case_id')
            ->orderby('elicitation_hasil_yang_dicapai.created_at','DESC');;
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
                'url' => route('open.data.elicit-result.index')
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
            Column::make('pelaksanaan_kegiatan')
                ->name('pelaksanaan_kegiatan')
                ->title('Pelaksanaan Kegiatan')
                ->className('text-center')
                ->footer('Pelaksanaan Kegiatan'),
            Column::make('download_report_hasil_pelaksanaan_tugas')
                ->name('download_report_hasil_pelaksanaan_tugas')
                ->title('UNDUH REPORT HASIL PELAKSANAAN TUGAS')
                ->className('text-center')
                ->footer('UNDUH REPORT HASIL PELAKSANAAN TUGAS')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ,
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
        return 'ElicitationResult_' . date('YmdHis');
    }
}
