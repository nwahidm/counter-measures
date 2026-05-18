<?php

namespace App\DataTables\Infiltration;

use App\Models\Infiltration\InfiltrationResultAchievement;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InfiltrationResultAchievementDataTable extends DataTable
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
            ->editColumn('operasi_rahasia', function ($data) {
                if($data->InfiltrationSecretOperation != null){
                    return Str::limit(strip_tags($data->InfiltrationSecretOperation->nama_operasi_rahasia), 128, '');
            
                }
                return Str::limit(strip_tags(""), 128, '');
            
            })

            ->editColumn('dinamika_target', function ($data) {
                if($data->InfiltrationTargetDynamics != null){
                    return Str::limit(strip_tags($data->InfiltrationTargetDynamics->dinamika_teramati), 128, '');
            
                }
                return Str::limit(strip_tags(""), 128, '');
            })

            ->editColumn('upload_hasil_yang_dicapai', function ($data) {
                if (!$data->upload_hasil_yang_dicapai) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.infiltration.result-achievement.download-file', encrypt($data->upload_hasil_yang_dicapai));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })

            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.infiltration.result-achievement.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.infiltration.result-achievement.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.infiltration.result-achievement.action-completed', compact('data'))->render();
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
     * @param \App\Models\Research\InfiltrationResultAchievement $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InfiltrationResultAchievement $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $id_satker = $satker->id_satker;
        return $model->newQuery()
            ->with(['InfiltrationTargetDynamics', 'InfiltrationSecretOperation', 'case', 'satker'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function($q) use ($user, $satker, $id_satker) {
                    $q->where('infiltration_hasil_yang_dicapai.satker_id', $id_satker);
                }
            )->orderBy('infiltration_hasil_yang_dicapai.created_at', 'desc');;
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
                'url' => route('close.infiltration.result-achievement.index')
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

            Column::make('dinamika_target')
                ->name('InfiltrationTargetDynamics.dinamika_teramati')
                ->title('TARGET')
                ->className('text-left')
                ->footer('TARGET'),

             Column::make('operasi_rahasia')
                ->name('InfiltrationSecretOperation.nama_operasi_rahasia')
                ->title('OPERASI RAHASIA')
                ->className('text-left')
                ->footer('OPERASI RAHASIA'),

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
        return 'infiltration_result_achievement-' . date('YmdHis');
    }
}
