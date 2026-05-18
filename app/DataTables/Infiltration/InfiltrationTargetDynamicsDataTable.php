<?php

namespace App\DataTables\Infiltration;

use App\Models\Infiltration\InfiltrationTargetDynamics;
use Illuminate\Support\Facades\DB;  // Import the DB facade
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InfiltrationTargetDynamicsDataTable extends DataTable
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
            ->editColumn('satker', function ($data) {
                return Str::limit(strip_tags($data->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case_name), 128, '');
            })
            ->editColumn('nama_operasi_rahasia', function ($data) {
                return Str::limit(strip_tags($data->nama_operasi_rahasia), 128, '');
            })
            ->editColumn('dinamika_teramati', function ($data) {
                return Str::limit(strip_tags($data->dinamika_teramati), 128, '');
            })
            ->editColumn('tanggal_dinamika_teramati', function ($data) {
                return Str::limit(strip_tags($data->tanggal_dinamika_teramati), 128, '');
            })
            ->editColumn('deskripsi_dinamika_teramati', function ($data) {
                return Str::limit(strip_tags($data->deskripsi_dinamika_teramati), 128, '');
            })
            ->editColumn('dinamika_target_dokumen_upload', function ($data) {
                if (!$data->dinamika_target_dokumen_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }
            
                $link = route('close.infiltration.target-dynamics.download-file', encrypt($data->dinamika_target_dokumen_upload));
            
                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fa fa-file-download"></i></a>';
            })
            ->editColumn('dinamika_target_video_upload', function ($data) {
                if (!$data->dinamika_target_video_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.infiltration.target-dynamics.download-file', encrypt($data->dinamika_target_video_upload));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.infiltration.target-dynamics.action', compact('data'))->render();
            // })

            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.infiltration.target-dynamics.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.infiltration.target-dynamics.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'dinamika_target_dokumen_upload',
                'dinamika_target_video_upload',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Delineation\DelineationInformationVerification $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InfiltrationTargetDynamics $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        if($user->user_roles == "superadmin"){
            return $model->newQuery()
            ->select([
                'infiltration_dinamika_target.*',
                'infiltration_operasi_rahasia.nama_operasi_rahasia', // Include all columns from delineation_information_verification
                'master_satker.nama_satker', // Example of including specific columns from joined tables
                'close_case.case_name', // Adjust based on actual column names
            ])
            ->leftJoin('infiltration_operasi_rahasia', DB::raw("infiltration_operasi_rahasia.id::text"), '=', DB::raw("infiltration_dinamika_target.infiltration_operasi_rahasia_id::text"))

            ->leftJoin('master_satker', DB::raw("CAST(infiltration_dinamika_target.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
            ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("infiltration_dinamika_target.case_id::text"))
            ->orderBy('created_at', 'desc');;
        }else{
            return $model->newQuery()
            ->select([
                'infiltration_dinamika_target.*',
                'infiltration_operasi_rahasia.nama_operasi_rahasia', // Include all columns from delineation_information_verification
                'master_satker.nama_satker', // Example of including specific columns from joined tables
                'close_case.case_name', // Adjust based on actual column names
            ])
            ->leftJoin('infiltration_operasi_rahasia', DB::raw("infiltration_operasi_rahasia.id::text"), '=', DB::raw("infiltration_dinamika_target.infiltration_operasi_rahasia_id::text"))

            ->leftJoin('master_satker', DB::raw("CAST(infiltration_dinamika_target.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
            ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("infiltration_dinamika_target.case_id::text"))
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($idSatker) {
                    $q->where('infiltration_dinamika_target.satker_id', $idSatker);
                }
            )->orderBy('created_at', 'desc');

        }
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
                'url' => route('close.infiltration.target-dynamics.index')
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

            Column::make('satker')
                ->name('master_satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('kasus')
                ->name('close_case.case_name')
                ->title('KASUS')
                ->className('text-left')
                ->footer('kasus'),

            Column::make('nama_operasi_rahasia')
                ->name('infiltration_operasi_rahasia.nama_operasi_rahasia')
                ->title('Nama Operasi Rahasia')
                ->className('text-left')
                ->footer('nama_operasi_rahasia'),

            Column::make('dinamika_teramati')
                ->name('dinamika_teramati')
                ->title('DINAMIKA TERAMATI')
                ->className('text-left')
                ->footer('dinamika_teramati'),
            
            Column::make('deskripsi_dinamika_teramati')
                ->name('deskripsi_dinamika_teramati')
                ->title('DESKRIPSI DINAMIKA TERAMATI')
                ->className('text-left')
                ->footer('deskripsi_dinamika_teramati'),


            Column::make('tanggal_dinamika_teramati')
                ->name('tanggal_dinamika_teramati')
                ->title('TANGGAL DINAMIKA TERAMATI')
                ->className('text-left')
                ->footer('tanggal_dinamika_teramati'),

            Column::make('dinamika_target_dokumen_upload')
                ->name('dinamika_target_dokumen_upload')
                ->title('DINAMIKA TARGET DOKUMEN')
                ->className('text-left')
                ->footer('dinamika_target_video_upload')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('dinamika_target_video_upload')
                ->name('dinamika_target_video_upload')
                ->title('DINAMIKA TARGET VIDEO')
                ->className('text-left')
                ->footer('METODE VERIFIKASI')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

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
        return 'research_lapinsus-' . date('YmdHis');
    }
}
