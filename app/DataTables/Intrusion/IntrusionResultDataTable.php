<?php

namespace App\DataTables\Intrusion;

use App\Models\Intrusion\IntrusionResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IntrusionResultDataTable extends DataTable
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
            ->editColumn('intrusion_target_lokasi.target_name', function ($data) {
                return Str::limit(strip_tags($data->location?->target_name), 128, '');
            })
            ->editColumn('intrusion_target_lokasi.lokasi_target', function ($data) {
                return Str::limit(strip_tags($data->location?->lokasi_target), 128, '');
            })
            ->editColumn('intrusion_lingkungan_target.nama_lingkungan', function ($data) {
                return Str::limit(strip_tags($data->environment?->nama_lingkungan), 128, '');
            })
            ->editColumn('hasil_yang_dicapai', function ($data) {
                return Str::limit(strip_tags($data->hasil_yang_dicapai), 128, '');
            })
            ->editColumn('upload_hasil_yang_dicapai', function ($data) {
                if (!$data->upload_hasil_yang_dicapai) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.intrusion.result.download-file', encrypt($data->upload_hasil_yang_dicapai));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.intrusion.result.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.intrusion.result.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.intrusion.result.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'upload_hasil_yang_dicapai',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Research\IntrusionResult $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(IntrusionResult $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        
        return $model->newQuery()
                ->when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                    $q->where('intrusion_hasil_yang_dicapai.satker_id', '=', $idSatker);
                })
                ->leftJoin('documents', 'documents.relation_id', DB::raw('intrusion_hasil_yang_dicapai.id::text'))
                ->leftJoin('master_satker', DB::raw('master_satker.id_satker::text'), 'intrusion_hasil_yang_dicapai.satker_id')
                ->leftJoin('close_case', 'close_case.id', 'intrusion_hasil_yang_dicapai.case_id')
                ->leftJoin('intrusion_lingkungan_target', 'intrusion_lingkungan_target.id', 'intrusion_hasil_yang_dicapai.intrusion_target_environment_id')
                ->leftJoin('intrusion_target_lokasi', 'intrusion_target_lokasi.id', 'intrusion_hasil_yang_dicapai.intrusion_target_location_id')
                ->select('intrusion_hasil_yang_dicapai.*',  'documents.doc_status_remark')
                ->orderBy('created_at', 'desc');;
                // ->select(
                //     'observation_surat_perintah.*',
                //     'master_satker.nama_satker',
                // );
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
                'url' => route('close.intrusion.result.index')
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
                ->footer('KASUS'),
            
            Column::make('intrusion_target_lokasi.target_name')
                ->name('intrusion_target_lokasi.target_name')
                ->title('NAMA TARGET')
                ->className('text-left')
                ->footer('NAMA TARGET'),

            Column::make('intrusion_target_lokasi.lokasi_target')
                ->name('intrusion_target_lokasi.lokasi_target')
                ->title('LOKASI TARGET')
                ->className('text-left')
                ->footer('LOKASI TARGET'),

            Column::make('intrusion_lingkungan_target.nama_lingkungan')
                ->name('intrusion_lingkungan_target.nama_lingkungan')
                ->title('NAMA LINGKUNGAN')
                ->className('text-left')
                ->footer('NAMA LINGKUNGAN'),

            Column::make('hasil_yang_dicapai')
                ->name('hasil_yang_dicapai')
                ->title('HASIL PENYURUPAN')
                ->className('text-left')
                ->footer('HASIL PENYURUPAN'),

            Column::make('upload_hasil_yang_dicapai')
                ->name('upload_hasil_yang_dicapai')
                ->title('FILE HASIL')
                ->className('text-center')
                ->footer('FILE HASIL'),

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
