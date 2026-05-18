<?php

namespace App\DataTables\Interogation;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\Models\InterogationResultAchievement;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class InterogationResultAchievementDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('nama_satker', function ($data) {
                return Str::limit($data->satker?->nama_satker, 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case->nama_kasus), 128, '');
            })
            ->editColumn('target_name', function ($data) {
                // return json_encode($data->interogrecordd);
                if ($data->interogrecord != null ) {
                    if($data->interogrecord->target_name != null){
                        return Str::limit(strip_tags($data->interogrecord->target_name), 128, '');
                        
                    }
                    
                }
                return Str::limit(strip_tags(""), 128, '');
            })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.open.interogation-result-achievement.action', compact('data'))->render();
                } else {
                    return view('backoffice.open.interogation-result-achievement.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'action', 'hasil_yang_dicapai'
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(InterogationResultAchievement $model): QueryBuilder
    {
        $user = auth()->user();
        $idSatker = $user->satker->id_satker;
        return $model->newQuery()
        ->with(['interogRecord', 'interoggtarget', 'case', 'satker'])
        ->when(
            !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
            function ($q) use ($idSatker) {
                $q->where(function ($query) use ($idSatker) {
                    $query->whereHas('case', function ($caseQuery) use ($idSatker) {
                        $caseQuery->where('id_satker', $idSatker);
                    })
                    ->orWhereHas('satker', function ($satkerQuery) use ($idSatker) {
                        $satkerQuery->where('parent_id', $idSatker);
                    });
                });
            }
        )
        ->orderby('interrogation_hasil_yang_dicapai.created_at','DESC');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('open.data.interogg-achieve.index')
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
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('NO')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('NO'),
            Column::make('nama_satker')
                ->name('satker.nama_satker')
                ->title('NAMA SATKER')
                ->className('text-center')
                ->footer('NAMA SATKER'),
            Column::make('kasus')
                ->name('case.nama_kasus')
                ->title('KASUS')
                ->className('text-center')
                ->footer('KASUS'),
            Column::make('hasil_yang_dicapai')
                ->name('hasil_yang_dicapai')
                ->title('HASIL DICAPAI')
                ->className('text-center')
                ->footer('HASIL DICAPAI'),
            Column::make('action')
                ->title('Aksi')
                ->className('text-center')
                ->footer('Aksi')
                ->width(150)
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'InterogationResultAchievement_' . date('YmdHis');
    }
}
