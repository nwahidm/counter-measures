<?php
namespace App\DataTables;

use Illuminate\Support\Str;
use App\Models\ExplorationResultAchievment;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use App\DataTables\LazyDataTablesExportHandler;

class ExplorationResultAchievmentDataTable extends DataTable
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
            ->editColumn('hasil_yang_dicapai', function ($data) {
                return Str::limit(strip_tags($data->hasil_yang_dicapai), 128, '');
            })
            ->editColumn('upload_hasil_yang_dicapai', function ($data) {
                if (!$data->upload_hasil_yang_dicapai) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.exploration.hasil-pencapaian.collect-info.download-file', encrypt($data->upload_hasil_yang_dicapai));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.exploration.hasilcapaian.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.exploration.hasilcapaian.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.exploration.hasilcapaian.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'upload_hasil_yang_dicapai',
                'action'
            ]);
    }

    public function query(ExplorationResultAchievment $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        return $model->newQuery()
                    ->select(
                        'exploration_hasil_yang_dicapai.*',
                        // 'exploration_hasil_yang_dicapai.exploration_target_identity_id',
                        // 'exploration_hasil_yang_dicapai.exploration_rencana_aksi_id',
                        // 'exploration_hasil_yang_dicapai.hasil_yang_dicapai',
                        // 'exploration_hasil_yang_dicapai.upload_hasil_yang_dicapai',
                        'master_satker.nama_satker',
                        'close_case.case_name'
                    )
                    ->leftJoin('master_satker', DB::raw("CAST(exploration_hasil_yang_dicapai.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
                    ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("exploration_hasil_yang_dicapai.case_id::text"))
                    ->when(
                        !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                        function ($q) use ($idSatker) {
                            $q->where('exploration_hasil_yang_dicapai.satker_id', 'like', "$idSatker%");
                        }
                    )
                    ->orderBy('master_satker.nama_satker')->orderBy('exploration_hasil_yang_dicapai.created_at', 'desc');;
    }

    public function html()
    {
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('close.exploration.hasil-pencapaian.index')
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
                ->title('NAMA KASUS')
                ->className('text-left')
                ->footer('NAMA KASUS'),

            Column::make('hasil_yang_dicapai')
                ->name('hasil_yang_dicapai')
                ->title('Hasil Dicapai')
                ->className('text-left')
                ->footer('Hasil Dicapai'),

            Column::make('upload_hasil_yang_dicapai')
                ->name('upload_hasil_yang_dicapai')
                ->title('FILE INFORMASI')
                ->className('text-center')
                ->footer('FILE INFORMASI'),

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
        return 'Hasil-dicapai-'. date('YmdHis');;
    }

}

