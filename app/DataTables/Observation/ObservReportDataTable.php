<?php

namespace App\DataTables\Observation;

use App\Models\CloseCase;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Services\DataTable;

class ObservReportDataTable extends DataTable
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
            ->editColumn('master_satker.nama_satker', function ($data) {
                return Str::limit(strip_tags($data->satker?->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case_name), 128, '');
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
                       $folderPath =Storage::url('close/case/' . $imagePaths[0]);
                   }
                    
                } 
        
                // Tampilkan gambar dengan tag HTML
                return '<img src="' . $folderPath . '" alt="Foto Target" class="img-thumbnail" width="50" height="50">';
            }) 
            ->editColumn('delineation_download_report_file', function ($data) {
               $link = route('close.delineation.report.download-file', $data->id);

               return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
           })
            ->editColumn('download_report', function ($data) {
                $link = route('close.observation.report.download-file', encrypt($data->id));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
            })
            ->rawColumns([
                'download_report',
                'target_photo'
            ]);
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
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($user, $satker, $idSatker) {
                    $q->where('close_case.satker_id', 'like', "$idSatker");
                }
            )
            ->join('master_satker', 'master_satker.id_satker', 'close_case.satker_id')
            ->select('master_satker.nama_satker', 'close_case.*')
            ->orderBy('created_at', 'desc');
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
                'url' => route('close.observation.report.index')
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

            Column::make('master_satker.nama_satker')
                ->name('master_satker.nama_satker')
                ->title('Satuan Kerja')
                ->className('text-left')
                ->footer('Satuan Kerja'),

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

            Column::make('download_report')
                ->name('download_report')
                ->title('UNDUH REPORT')
                ->className('text-center')
                ->orderable(false)
                 ->searchable(false)
                 ->exportable(false)
                ->footer('UNDUH REPORT'),

            /*Column::make('action')
                ->title('AKSI')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
                ->footer('AKSI'),*/
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'research_report-' . date('YmdHis');
    }
}
