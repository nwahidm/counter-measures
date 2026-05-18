<?php

namespace App\DataTables\Observation;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;
use App\Models\Observation\ObservCollectInfo;

class ObservCollectInfoDataTable extends DataTable
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
            ->editColumn('master_satker.nama_satker', function ($data) {
                return Str::limit(strip_tags($data->satker?->nama_satker), 128, '');
            })
            ->editColumn('close_case.case_name', function ($data) {
                return Str::limit(strip_tags($data->case?->case_name), 128, '');
            })
            ->editColumn('observation_surat_perintah.surat_perintah_number', function ($data) {
                return Str::limit(strip_tags($data->sprint?->surat_perintah_number), 128, '');
            })
            ->editColumn('observation_surat_perintah.surat_perintah_date', function ($data) {
                return $data->sprint?->surat_perintah_date?->isoFormat('DD-MM-YYYY');
            })
            ->editColumn('information_collection_perihal', function ($data) {
                return $data->information_collection_perihal;
            })
            ->editColumn('upload_info', function ($data) {
                if (!$data->information_collection_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.observation.collect-info.download-file', encrypt($data->information_collection_upload));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.observation.collect-info.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.observation.collect-info.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.observation.collect-info.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'upload_info',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Research\ObservCollectInfo $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ObservCollectInfo $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        
        return $model->newQuery()
                ->when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                    $q->where('observation_information_collection.satker_id', '=', $idSatker);
                })
                ->leftJoin('documents', 'documents.relation_id', DB::raw('observation_information_collection.id::text'))
                ->leftJoin('close_case', 'close_case.id', 'observation_information_collection.case_id')
                ->leftJoin('observation_surat_perintah', 'observation_surat_perintah.id', 'observation_information_collection.surat_perintah_id')
                ->leftJoin('master_satker', 'master_satker.id_satker', 'close_case.satker_id')
                ->select('close_case.case_name', 'master_satker.nama_satker', 'observation_information_collection.*', 'documents.doc_status_remark', )
                ->orderBy('observation_information_collection.created_at', 'desc');
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
                'url' => route('close.observation.collect-info.index')
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
                ->title('NO ')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('NO'),

            Column::make('master_satker.nama_satker')
                ->name('master_satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('close_case.case_name')
                ->name('close_case.case_name')
                ->title('KASUS')
                ->className('text-left')
                ->footer('kasus'),

            Column::make('observation_surat_perintah.surat_perintah_number')
                ->name('observation_surat_perintah.surat_perintah_number')
                ->title('NOMOR SURAT PERINTAH')
                ->className('text-left')
                ->footer('NOMOR SURAT PERINTAH'),

            Column::make('observation_surat_perintah.surat_perintah_date')
                ->name('observation_surat_perintah.surat_perintah_date')
                ->title('TGL. SURAT PERINTAH')
                ->className('text-center')
                ->footer('TGL. SURAT PERINTAH'),

            Column::make('information_collection_perihal')
                ->name('information_collection_perihal')
                ->title('PERIHAL INFORMASI')
                ->className('text-left')
                ->footer('PERIHAL INFORMASI'),

            Column::make('upload_info')
                ->name('upload_info')
                ->title('FILE INFORMASI')
                ->className('text-center')
                ->footer('FILE INFORMASI')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('doc_status_remark')
                ->name('documents.doc_status_remark')
                ->title('ANALISIS DOKUMEN')
                ->className('text-center')
                ->footer('ANALISIS DOKUMEN'),

            Column::make('action')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('140px')
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
        return 'observation_surat_perintah-' . date('YmdHis');
    }
}
