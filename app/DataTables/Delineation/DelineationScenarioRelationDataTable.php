<?php

namespace App\DataTables\Delineation;


use App\Models\Delineation\DelineationScenarioRelation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DelineationScenarioRelationDataTable extends DataTable
{
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
            ->editColumn('information_collection_source', function ($data) {
                return Str::limit(strip_tags($data->information_collection_source), 128, '');
            })
            ->editColumn('information_collection_perihal', function ($data) {
                return Str::limit(strip_tags($data->information_collection_perihal), 128, '');
            })
            ->editColumn('subjek_utama', function ($data) {
                return Str::limit(strip_tags($data->subjek_utama), 128, '');
            })
            ->editColumn('jenis_relasi', function ($data) {
                return Str::limit(strip_tags($data->jenis_relasi), 128, '');
            })
            ->editColumn('kekuatan_relasi', function ($data) {
                return Str::limit(strip_tags($data->kekuatan_relasi), 128, '');
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.delineation.scenario-relation.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.delineation.scenario-relation.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.delineation.scenario-relation.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns(['action']);
    }

    public function query(DelineationScenarioRelation $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        if($user->user_roles == "superadmin"){
            return $model->newQuery()
            ->select([
                'observation_information_collection.id as observation_id',
                'delineation_scenario_relation.*',
                'master_satker.nama_satker',
                'close_case.case_name',
                // 'case_close_progresses.*',
                'observation_information_collection.information_collection_source',
                'observation_information_collection.information_collection_perihal'
            ])
            // ->leftJoin('case_close_progresses', DB::raw("case_close_progresses.case_id::text"), '=', DB::raw("delineation_scenario_relation.case_id::text"))
            
            ->leftJoin('master_satker', DB::raw("CAST(delineation_scenario_relation.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
            ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("delineation_scenario_relation.case_id::text"))
            ->leftJoin('observation_information_collection', DB::raw("observation_information_collection.id::text"), '=', DB::raw("delineation_scenario_relation.information_collection_id::text"))
            ->orderBy('delineation_scenario_relation.created_at', 'desc');
        }else{
            return $model->newQuery()
            ->select([
                'observation_information_collection.id as observation_id',
                'delineation_scenario_relation.*',
                'master_satker.nama_satker',
                'close_case.case_name',
                // 'case_close_progresses.*',
                'observation_information_collection.information_collection_source',
                'observation_information_collection.information_collection_perihal'
            ])
            // ->leftJoin('case_close_progresses', DB::raw("case_close_progresses.case_id::text"), '=', DB::raw("delineation_scenario_relation.case_id::text"))
            
            ->leftJoin('master_satker', DB::raw("CAST(delineation_scenario_relation.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
            ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("delineation_scenario_relation.case_id::text"))
            ->leftJoin('observation_information_collection', DB::raw("observation_information_collection.id::text"), '=', DB::raw("delineation_scenario_relation.information_collection_id::text"))
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($idSatker) {
                    $q->where('delineation_scenario_relation.satker_id', $idSatker);
                }
            )
            ->orderBy('delineation_scenario_relation.created_at', 'desc');;
        }

       
    }

    public function html()
    {
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('close.delineation.scenario-relation.index')
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
                ->footer('KASUS'),

            Column::make('information_collection_source')
                ->name('observation_information_collection.information_collection_source')
                ->title('SUMBER INFORMASI')
                ->className('text-left')
                ->footer('SUMBER INFORMASI'),

            Column::make('information_collection_perihal')
                ->name('observation_information_collection.information_collection_perihal')
                ->title('PERIHAL INFORMASI')
                ->className('text-left')
                ->footer('information_collection_perihal'),

            Column::make('subjek_utama')
                ->name('subjek_utama')
                ->title('SUBJEK UTAMA')
                ->className('text-left')
                ->footer('subjek_utama'),


            Column::make('jenis_relasi')
                ->name('jenis_relasi')
                ->title('JENIS RELASI')
                ->className('text-left')
                ->footer('JENIS RELASI'),

            Column::make('kekuatan_relasi')
                ->name('kekuatan_relasi')
                ->title('KEKUATAN RELASI')
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

    protected function filename(): string
    {
        return 'research_lapinsus-' . date('YmdHis');
    }
}
