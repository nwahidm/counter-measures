<?php
namespace App\DataTables;

use Illuminate\Support\Str;
use App\Models\ExplorationRencanaAksi;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use App\DataTables\LazyDataTablesExportHandler;

class ExplorationRencanaAksiDataTable extends DataTable
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
            ->editColumn('rencana_aksi_data', function ($data) {
                return Str::limit(strip_tags($data->rencana_aksi_data), 128, '');
            })
            ->editColumn('rencana_aksi_detail', function ($data) {
                return Str::limit(strip_tags($data->rencana_aksi_detail), 128, '');
            })
            ->editColumn('rencana_aksi_upload', function ($data) {
                if (!$data->rencana_aksi_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.exploration.collect-info.download-file', encrypt($data->rencana_aksi_upload));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.exploration.rencanaaksi.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.exploration.rencanaaksi.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.exploration.rencanaaksi.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'rencana_aksi_upload',
                'action'
            ]);
    }

    public function query(ExplorationRencanaAksi $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        return $model->newQuery()
                    ->select(
                        'exploration_rencana_aksi.*',
                        // 'exploration_rencana_aksi.rencana_aksi_data as rencana_aksi_data',
                        // 'exploration_rencana_aksi.rencana_aksi_detail as rencana_aksi_detail',
                        // 'exploration_rencana_aksi.rencana_aksi_upload',
                        'master_satker.nama_satker',
                        'close_case.case_name'
                    )
                    ->leftJoin('master_satker', DB::raw("CAST(exploration_rencana_aksi.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
                    ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("exploration_rencana_aksi.case_id::text"))
                    ->when(
                        !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                        function ($q) use ($idSatker) {
                            $q->where('exploration_rencana_aksi.satker_id', 'like', "$idSatker%");
                        }
                    )
                    ->orderBy('master_satker.nama_satker')
                    ->orderBy('exploration_rencana_aksi.created_at', 'desc');
    }

    public function html()
    {
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('close.exploration.rencana-aksi.index')
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
                ->title('Nama Kasus ')
                ->className('text-left')
                ->footer('Nama Kasus'),

            Column::make('rencana_aksi_data')
                ->name('rencana_aksi_data')
                ->title('Rencana Aksi')
                ->className('text-left')
                ->footer('Rencana Aksi'),

            Column::make('rencana_aksi_detail')
                ->name('rencana_aksi_detail')
                ->title('Detail Rencana Aksi')
                ->className('text-left')
                ->footer('Detail Rencana Aksi'),

            Column::make('rencana_aksi_upload')
                ->name('rencana_aksi_upload')
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
        return 'research_lapinsus-'. date('YmdHis');;
    }

}

