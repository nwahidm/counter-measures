<?php

namespace App\DataTables\Tailing;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;
use App\Models\Tailing\TailingResultAchievement;

class TailingResultAchievementDataTable extends DataTable
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
                return Str::limit(strip_tags($data->satker->nama_satker), 128, '');
            })
             ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case->case_name), 128, '');
            })
            ->editColumn('hasil_yang_dicapai', function ($data) {
                return Str::limit(strip_tags($data->hasil_yang_dicapai), 128, '');
            })
            ->editColumn('target_name', function ($data) {
                return Str::limit(strip_tags($data->TailingPemahamanPerilaku?->target_name), 128, '');
            })

            ->editColumn('upload_hasil_yang_dicapai', function ($data) {
                if (!$data->upload_hasil_yang_dicapai) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.tailing.result-achievement.download-file', encrypt($data->upload_hasil_yang_dicapai));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
            })

            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.tailing.result-achievement.action', compact('data'))->render();
            // })

            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.tailing.result-achievement.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.tailing.result-achievement.action-completed', compact('data'))->render();
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
     * @param \App\Models\Research\TailingResultAchievement $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TailingResultAchievement $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $kodeSatker = $satker->kode_satker;
        return $model->newQuery()
        ->with(['TailingTargetOperasi', 'TailingPemahamanPerilaku', 'case', 'satker', 'Documents'])
        ->join("documents", "documents.relation_id","=","tailing_hasil_yang_dicapai.id")
            ->select(
                    "tailing_hasil_yang_dicapai.*",
                    DB::raw("(CASE 
                                WHEN documents.doc_status = '8' THEN 'Error'
                                ELSE documents.doc_status_remark END) AS doc_status_remark"),
                    "documents.doc_status"
                )
            ->when(
                !$user->hasRole(["superadmin", "admin-kejagung", "jaksa-penegakkan-hukum"]),
                function($q) use ($user, $satker, $kodeSatker) {
                    $q->where("tailing_hasil_yang_dicapai.kode_satker", "like", "$kodeSatker%");
                }
            )->orderBy('created_at', 'desc');
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
                'url' => route('close.tailing.result-achievement.index')
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

            Column::make('satker')
                ->name('satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('kasus')
                ->name('case.case_name')
                ->title('KASUS')
                ->className('text-left')
                ->footer('KASUS'),

            Column::make('target_name')
                ->name('TailingPemahamanPerilaku.target_name')
                ->title('TARGET')
                ->className('text-left')
                ->footer('TARGET'),

            Column::make('hasil_yang_dicapai')
                ->name('hasil_yang_dicapai')
                ->title('HASIL YANG DICAPAI')
                ->className('text-left')
                ->footer('HASIL YANG DICAPAI'),

            Column::make('upload_hasil_yang_dicapai')
                ->name('upload_hasil_yang_dicapai')
                ->title('FILE')
                ->className('text-center')
                ->footer('FILE')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

             Column::make('doc_status_remark')
                ->name('Documents.doc_status_remark')
                ->title('STATUS')
                ->className('text-left')
                ->footer('STATUS'),

            Column::make('action')
                ->title('AKSI')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
                ->footer('AKSI'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'tailing_result_achievement-' . date('YmdHis');
    }
}
