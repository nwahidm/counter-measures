<?php

namespace App\DataTables\Infiltration;

use App\Models\Infiltration\InfiltrationSecretOperation;
use Illuminate\Support\Facades\DB;  // Import the DB facade
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InfiltrationSecretOperationDataTable extends DataTable
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
            ->editColumn('tanggal_operasi_rahasia', function ($data) {
                return Str::limit(strip_tags($data->tanggal_operasi_rahasia), 128, '');
            })
            ->editColumn('metode_eksekusi', function ($data) {
                return Str::limit(strip_tags($data->metode_eksekusi), 128, '');
            })
            ->editColumn('operasi_rahasia_dokumen_upload', function ($data) {
                if (!$data->operasi_rahasia_dokumen_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }
            
                $link = route('close.infiltration.secret-operation.download-file', encrypt($data->operasi_rahasia_dokumen_upload));
            
                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fa fa-file-download"></i></a>';
            })
            ->editColumn('operasi_rahasia_video_upload', function ($data) {
                if (!$data->operasi_rahasia_video_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.infiltration.secret-operation.download-file', encrypt($data->operasi_rahasia_video_upload));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.infiltration.secret-operation.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.infiltration.secret-operation.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.infiltration.secret-operation.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'operasi_rahasia_dokumen_upload',
                'operasi_rahasia_video_upload',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Delineation\DelineationInformationVerification $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InfiltrationSecretOperation $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        if($user->user_roles == "superadmin"){
            return $model->newQuery()
            ->select([
                'infiltration_operasi_rahasia.*', // Include all columns from delineation_information_verification
                'master_satker.nama_satker', // Example of including specific columns from joined tables
                'close_case.case_name', // Adjust based on actual column names
            ])
            ->leftJoin('master_satker', DB::raw("CAST(infiltration_operasi_rahasia.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
            ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("infiltration_operasi_rahasia.case_id::text"))
            ->orderBy('created_at', 'desc');
        }else{
            return $model->newQuery()
            ->select([
                'infiltration_operasi_rahasia.*', // Include all columns from delineation_information_verification
                'master_satker.nama_satker', // Example of including specific columns from joined tables
                'close_case.case_name', // Adjust based on actual column names
            ])
            ->leftJoin('master_satker', DB::raw("CAST(infiltration_operasi_rahasia.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
            ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("infiltration_operasi_rahasia.case_id::text"))
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($idSatker) {
                    $q->where('infiltration_operasi_rahasia.satker_id', $idSatker);
                }
            )->orderBy('created_at', 'desc');;

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
                'url' => route('close.infiltration.secret-operation.index')
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
                ->name('nama_operasi_rahasia')
                ->title('Nama Operasi Rahasia')
                ->className('text-left')
                ->footer('nama_operasi_rahasia'),

            Column::make('metode_eksekusi')
                ->name('metode_eksekusi')
                ->title('Metode Eksekusi')
                ->className('text-left')
                ->footer('metode_eksekusi'),

            Column::make('tanggal_operasi_rahasia')
                ->name('tanggal_operasi_rahasia')
                ->title('TANGGAL OPERASI RAHASIA')
                ->className('text-left')
                ->footer('tanggal_operasi_rahasia'),

            Column::make('operasi_rahasia_dokumen_upload')
                ->name('operasi_rahasia_dokumen_upload')
                ->title('OPERASI RAHASIA DOKUMEN')
                ->className('text-left')
                ->footer('operasi_rahasia_dokumen_upload'),

            Column::make('operasi_rahasia_video_upload')
                ->name('operasi_rahasia_video_upload')
                ->title('OPERASI RAHASIA VIDEO')
                ->className('text-left')
                ->footer('METODE VERIFIKASI'),

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
