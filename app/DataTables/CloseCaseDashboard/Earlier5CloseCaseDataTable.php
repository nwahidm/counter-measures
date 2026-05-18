<?php

namespace App\DataTables\CloseCaseDashboard;

use Carbon\Carbon;
use App\Models\CloseCase;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class Earlier5CloseCaseDataTable extends DataTable
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
            ->editColumn('progress', function ($data) {
                return $data->progress?->percentage ? $data->progress?->percentage . ' %' : '0 %';
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.close.case.action', compact('data'))->render();
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(CloseCase $model): QueryBuilder
    {

        $user = auth()->user();
        $bulan = $this->request()->input('bulan', now()->format('Y-m'));

        $filterSatker = $this->request()->input('satker') ?? $user->id_satker;
        if ($bulan) {
            $tanggalPertama = Carbon::parse($bulan)->startOfMonth()->toDateString();
            $tanggalTerakhir = Carbon::parse($bulan)->endOfMonth()->toDateString();
        } else {
            $tanggalPertama = Carbon::now()->startOfMonth()->toDateString();
            $tanggalTerakhir = Carbon::now()->endOfMonth()->toDateString();
        }
        
        return $model->newQuery()
                        ->join('master_satker', 'close_case.satker_id', '=', 'master_satker.id_satker')
                        // ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                        //     $q->where('close_case.satker_id', '=', $user->id_satker);
                        // })
                        ->where('close_case.satker_id', $filterSatker)
                        ->whereRaw("close_case.created_at::date between '{$tanggalPertama}' and '{$tanggalTerakhir}'")
                        ->orderBy('created_at', 'asc')
                        ->limit(5)
                        ->select(
                            'close_case.*',
                            'master_satker.nama_satker',
                        );
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
            ->pageLength(5)
            ->columns($this->getColumns())
            ->postAjax([
                'url' => ('earlier'),
                'data' => 'function(d) {
                    d.bulan = $("input[name=bulan]").val();
                    d.satker = $("select[name=satker]").val();
                }',
            ])
            ->buttons(
                Button::make('excel')->className('btn-light btn-sm'),
                Button::make('reset')->className('btn-light btn-sm')
            )
            ->dom($domOption)
            ->parameters([
                'initComplete' => "function () {
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
                ->title('No')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center'),

            // Column::make('nama_satker')
            //     ->name('master_satker.nama_satker')
            //     ->title('Nama Satker')
            //     ->footer('Nama Satker'),
            
            Column::make('case_name')
                ->title('Nama Kasus'),

            Column::make('case_date')
                ->title('Tanggal Kasus'),

            Column::make('target_name')
                ->title('Nama Target'),

            Column::make('progress')
                ->title('Progres')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false),

            Column::make('action')
                ->title('Aksi')
                ->className('text-center')
                ->width(140)
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
        return 'Earlier5CloseCase_' . date('YmdHis');
    }
}
