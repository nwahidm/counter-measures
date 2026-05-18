<?php

namespace App\DataTables\Observation;

use App\Models\Observation\ObservDirective;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ObservDirectiveDataTable extends DataTable
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
            ->editColumn('surat_perintah_number', function ($data) {
                return Str::limit(strip_tags($data->surat_perintah_number), 128, '');
            })
            ->editColumn('surat_perintah_perihal', function ($data) {
                return Str::limit(strip_tags($data->surat_perintah_perihal), 128, '');
            })
            ->editColumn('surat_perintah_date', function ($data) {
                return $data->surat_perintah_date?->isoFormat('DD-MM-YYYY');
            })
            ->editColumn('surat_perintah_date_started', function ($data) {
                return $data->surat_perintah_date_started?->isoFormat('DD-MM-YYYY');
            })
            ->editColumn('upload_sprint', function ($data) {
                if (!$data->surat_perintah_path) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.observation.directive.download-file', encrypt($data->surat_perintah_path));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.observation.directive.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.observation.directive.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.observation.directive.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'upload_sprint',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Research\ObservDirective $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ObservDirective $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        
        return $model->newQuery()
                ->when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                    $q->where('observation_surat_perintah.satker_id', '=', $idSatker);
                })
                ->leftJoin('close_case', 'close_case.id', 'observation_surat_perintah.case_id')
                ->leftJoin('master_satker', 'master_satker.id_satker', 'close_case.satker_id')
                ->select('observation_surat_perintah.*', 'close_case.case_name', 'master_satker.nama_satker')
                ->orderBy('observation_surat_perintah.created_at', 'desc');
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
                'url' => route('close.observation.directive.index')
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

            Column::make('surat_perintah_number')
                ->name('surat_perintah_number')
                ->title('NOMOR SURAT PERINTAH')
                ->className('text-left')
                ->footer('NOMOR SURAT PERINTAH'),

            Column::make('surat_perintah_perihal')
                ->name('surat_perintah_perihal')
                ->title('PERIHAL SURAT PERINTAH')
                ->className('text-left')
                ->footer('PERIHAL SURAT PERINTAH'),

            Column::make('surat_perintah_date')
                ->name('surat_perintah_date')
                ->title('TGL. SURAT PERINTAH')
                ->className('text-center')
                ->footer('TGL. SURAT PERINTAH'),

            Column::make('surat_perintah_date_started')
                ->name('surat_perintah_date_started')
                ->title('TGL. MULAI SURAT PERINTAH')
                ->className('text-center')
                ->footer('TGL. MULAI SURAT PERINTAH'),

            Column::make('upload_sprint')
                ->name('upload_sprint')
                ->title('FILE SURAT PERINTAH')
                ->className('text-center')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->footer('FILE SURAT PERINTAH'),

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
