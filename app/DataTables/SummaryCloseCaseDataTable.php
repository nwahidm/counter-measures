<?php

namespace App\DataTables;

use App\Models\CloseCase;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\LazyDataTablesExportHandler;

class SummaryCloseCaseDataTable extends DataTable
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
            ->editColumn('progress', function ($data) {
                return $data->progress?->percentage ? $data->progress?->percentage . ' %'.' - '.$data->progress->status : '0 %'.' - '.$data->progress->status;
            })
            ->addColumn('target_photo', function ($row) {
                $folderName = $row->nama_satker;
                $folderName = strtolower(trim($row->nama_satker));
                $folderName = str_replace(" ","_", $folderName);
                $folderPath = asset('assets/images/placeholder.jpeg');
                
                if ($row->target_photo) {
                    $decoded = json_decode($row->target_photo);
                    if(count($decoded) > 0){
                        $imagePaths = $decoded;
                        $folderPath = Storage::url('close/case/' . $imagePaths[0]);
                    }
                    
                } 
        
                // Tampilkan gambar dengan tag HTML
                return '<img src="' . $folderPath . '" alt="Foto Target" class="img-thumbnail" width="50" height="50">';
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.close.summary.action', compact('data'))->render();
            })
            ->rawColumns(['action', 'target_photo']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CloseCase $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CloseCase $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;

        return $model->newQuery()
                        ->join('master_satker',DB::raw("CAST(close_case.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
                        ->join('case_close_progresses', "case_close_progresses.case_id" , '=', "close_case.id")
                        ->when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                            $q->where('close_case.satker_id', '=', "$idSatker");
                        })
                        ->where('case_close_progresses.percentage', '=', 100)
                        ->orderBy('master_satker.nama_satker')
                        ->select(
                            'close_case.*',
                            'master_satker.nama_satker',
                        );
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
                'url' => route('close.summary')
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
                ->title('No')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('No'),

            Column::make('nama_satker')
                ->name('master_satker.nama_satker')
                ->title('Nama Satker')
                ->footer('Nama Satker'),
            
            Column::make('case_name')
                ->title('Nama Kasus')
                ->footer('Nama Kasus'),

            Column::make('case_date')
                ->title('Tanggal Kasus')
                ->footer('Tanggal Kasus'),

            Column::make('target_name')
                ->title('Nama Target')
                ->footer('Nama Target'),

            Column::make('target_photo')
                ->title('Foto Target')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->footer('Foto Target'),
            
            Column::make('progress')
                ->name('case_close_progresses.percentage')
                ->title('Progres')
                ->footer('Progres'),

            Column::make('action')
                ->title('Aksi')
                ->width(150)
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
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
        return 'CloseCase_' . date('YmdHis');
    }
}
