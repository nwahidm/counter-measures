<?php

namespace App\DataTables\Delineation;


use App\Models\Delineation\DelineationInformationVerification;
use Illuminate\Support\Facades\DB;  // Import the DB facade
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DelineationInformationVerificationDataTable extends DataTable
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
            ->editColumn('nama_satker', function ($data) {
                return Str::limit(strip_tags($data->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case_name), 128, '');
            })
            ->editColumn('information_collection_source', function ($data) {
                return Str::limit(strip_tags($data->information_collection_source), 128, '');
            })
            ->editColumn('information_collection_perihal', function ($data) {
                return Str::limit(strip_tags($data->information_collection_perihal), 128, '');
            })
            ->editColumn('kredibilitas_sumber', function ($data) {
                return Str::limit(strip_tags($data->kredibilitas_sumber), 128, '');
            })
            ->editColumn('metode_verifikasi', function ($data) {
                return Str::limit(strip_tags($data->metode_verifikasi), 128, '');
            })
            ->editColumn('detail_informasi_verifikasi', function ($data) {
                return Str::limit(strip_tags($data->detail_informasi_verifikasi), 128, '');
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.delineation.information-verification.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.delineation.information-verification.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.delineation.information-verification.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'upload_lapinsus',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Delineation\DelineationInformationVerification $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DelineationInformationVerification $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        if($user->user_roles == "superadmin"){
            return $model->newQuery()
            ->select([
                'observation_information_collection.id as observation_id',
                'delineation_information_verification.*', // Include all columns from delineation_information_verification
                'master_satker.nama_satker', // Example of including specific columns from joined tables
                'close_case.case_name', // Adjust based on actual column names
                'observation_information_collection.information_collection_source',
                // 'case_close_progresses.*',
                'observation_information_collection.information_collection_perihal' // Adjust based on actual column names
            ])
            ->leftJoin('master_satker', DB::raw("CAST(delineation_information_verification.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
            ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("delineation_information_verification.case_id::text"))
            ->leftJoin('observation_information_collection', DB::raw("observation_information_collection.id::text"), '=', DB::raw("delineation_information_verification.information_collection_id::text"))
            ->orderBy('delineation_information_verification.created_at', 'desc');;
            // ->leftJoin('case_close_progresses', DB::raw("case_close_progresses.case_id::text"), '=', DB::raw("delineation_information_verification.case_id::text"));
            
            // ->when(
            //     !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
            //     function ($q) use ($kodeSatker) {
            //         $q->where('delineation_information_verification.satker_id', 'like', "$kodeSatker%");
            //     }
            // );
        }else{
            return $model->newQuery()
            ->select([
                'observation_information_collection.id as observation_id',
                'delineation_information_verification.*', // Include all columns from delineation_information_verification
                'master_satker.nama_satker', // Example of including specific columns from joined tables
                'close_case.case_name', // Adjust based on actual column names
                'observation_information_collection.information_collection_source',
                // 'case_close_progresses.*',
                'observation_information_collection.information_collection_perihal' // Adjust based on actual column names
            ])
            ->leftJoin('master_satker', DB::raw("CAST(delineation_information_verification.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
            ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("delineation_information_verification.case_id::text"))
            ->leftJoin('observation_information_collection', DB::raw("observation_information_collection.id::text"), '=', DB::raw("delineation_information_verification.information_collection_id::text"))
            // ->leftJoin('case_close_progresses', DB::raw("case_close_progresses.case_od::text"), '=', DB::raw("delineation_information_verification.case_id::text"))
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($idSatker) {
                    $q->where('delineation_information_verification.satker_id', $idSatker);
                }
            )->orderBy('delineation_information_verification.created_at', 'desc');;;
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
                'url' => route('close.delineation.information-verification.index')
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

            Column::make('nama_satker')
                ->name('master_satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('kasus')
                ->name('close_case.case_name')
                ->title('KASUS')
                ->className('text-left')
                ->footer('kasus'),

            Column::make('information_collection_source')
                ->name('observation_information_collection.information_collection_source')
                ->title('SUMBER INFORMASI')
                ->className('text-left')
                ->footer('information_collection_source'),

            Column::make('information_collection_perihal')
                ->name('observation_information_collection.information_collection_perihal')
                ->title('PERIHAL INFORMASI')
                ->className('text-left')
                ->footer('information_collection_perihal'),

            Column::make('kredibilitas_sumber')
                ->name('kredibilitas_sumber')
                ->title('KREDIBILITAS SUMBER')
                ->className('text-left')
                ->footer('kredibilitas_sumber'),

            Column::make('metode_verifikasi')
                ->name('metode_verifikasi')
                ->title('METODE VERIFIKASI')
                ->className('text-left')
                ->footer('METODE VERIFIKASI'),

            Column::make('detail_informasi_verifikasi')
                ->name('detail_informasi_verifikasi')
                ->title('DETAIL INFORMASI VERIFIKASI')
                ->className('text-left')
                ->footer('PERIHAL SURAT'),

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
