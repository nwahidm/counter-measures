<?php

namespace App\DataTables;

use App\Models\CloseCase;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\LazyDataTablesExportHandler;

class CloseCaseDataTable extends DataTable
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
                return $data->satker?->nama_satker;
            })
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
                        // $folderPath = asset('close_case_target_image/'. $folderName. '/'. $imagePaths[0]);
                        $folderPath =Storage::url('close/case/' . $imagePaths[0]);
                    }
                    
                } 
        
                // Tampilkan gambar dengan tag HTML
                return '<img src="' . $folderPath . '" alt="Foto Target" class="img-thumbnail" width="50" height="50">';
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.close.case.action', compact('data'))->render();
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

        return $model->newQuery()
                ->with(['satker'])
                ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                    $q->where('close_case.satker_id', $user->satker?->id_satker);
                })
                ->whereHas('satker', function($q){
                    $q->orderby('master_satker.nama_satker','ASC');    
                })
                ->orderby('close_case.created_at','DESC');
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
                'url' => route('close.case.index')
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
                ->name('satker.nama_satker')
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
                ->title('Progres')
                ->orderable(false)
                ->searchable(false)
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
