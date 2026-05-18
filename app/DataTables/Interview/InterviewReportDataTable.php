<?php

namespace App\DataTables\Interview;

use App\Models\OpenCase;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InterviewReportDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

    // public function dataTable($query)
    // {
    //     return datatables()
    //         ->eloquent($query)
    //         ->addIndexColumn()
    //         ->editColumn('satker', function ($data) {
    //             return Str::limit(strip_tags($data->satker->nama_satker), 128, '');
    //         })
    //         ->editColumn('nama_kasus', function ($data) {
    //             return Str::limit(strip_tags($data->nama_kasus), 128, '');
    //         })
    //         ->editColumn('download_report', function ($data) {
    //             $link = route('open.interview.report.download-report', encrypt($data->id));

    //             return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
    //         })
            
    //         // ->editColumn('download_wawancara', function ($data) {
    //         //     $link = route('open.interview.report.download-wawancara', $data->id);
    //         //     $data = OpenCase::join('interview_jadwal','open_case.id','interview_jadwal.case_id')
    //         //     ->join('interview_hasil','interview_jadwal.id_interview_scheduler','interview_hasil.interview_scheduler_id')
    //         //     ->join('interview_saran_dan_tindak_lanjut','interview_hasil.id_interview_result','interview_saran_dan_tindak_lanjut.interview_result_id')
    //         //     ->where('open_case.id', $data->id)
    //         //     ->first();
    //         //     if ($data) {
    //         //         return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
    //         //     }
    //         //     return '<h6>Belum Isi Form Wawancara</h6>';
    //         // })
    //         /*->editColumn('action', function ($data) {
    //             return view('backoffice.open.interview.report.action', compact('data'))->render();
    //         })*/
    //         ->rawColumns([
    //             'download_report','download_wawancara'
    //             //'action'
    //         ]);
    // }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('satker.nama_satker', function ($data) {
                return Str::limit(strip_tags($data->satker->nama_satker), 128, '');
            })
            ->editColumn('nama_kasus', function ($data) {
                return Str::limit(strip_tags($data->nama_kasus), 128, '');
            })
            ->editColumn('download_report', function ($data) {
                $link = route('open.interview.report.download-report', encrypt($data->id));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
            })->editColumn('foto', function ($row) {
                $folderName = $row->nama_satker;
                $folderPath = asset('assets/images/placeholder.jpeg');
                
                if ($row->foto) {
                    $decoded = json_decode($row->foto);
                    if(count($decoded) > 0){
                        $imagePaths = $decoded;
                        $folderPath = asset('storage/' . $imagePaths[0]);
                    }
                    
                } 
        
                // Tampilkan gambar dengan tag HTML
                return '<img src="' . $folderPath . '" alt="Foto Target" class="img-thumbnail" width="50" height="50">';
            }) 
            
            /*->editColumn('action', function ($data) {
                return view('backoffice.open.research.report.action', compact('data'))->render();
            })*/
            ->rawColumns([
                'foto',
                'download_report','download_lapinsus'
                //'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\OpenCase $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OpenCase $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;

        return $model->newQuery()
            ->with(['satker'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($user, $satker, $idSatker) {
                    $q->where('open_case.id_satker', 'like', "$idSatker");
                }
            )->orderBy('open_case.created_at', 'desc');
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
                'url' => route('open.interview.report.index')
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

            Column::make('satker.nama_satker')
                ->name('satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('nama_kasus')
                ->name('nama_kasus')
                ->title('KASUS')
                ->className('text-left')
                ->footer('KASUS'),

            Column::make('tanggal_kasus')
                ->title('Tanggal Kasus')
                ->footer('Tanggal Kasus'),

            Column::make('nama_target')
                ->title('Nama Target')
                ->footer('Nama Target'),

            Column::make('foto')
                ->title('Foto Target')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->footer('Foto Target'),

            Column::make('download_report')
                ->name('download_report')
                ->title('UNDUH REPORT')
                ->className('text-center')
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
        return 'interview_report-' . date('YmdHis');
    }
}
