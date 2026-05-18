<?php

namespace App\DataTables\Tailing;

use App\Models\Tailing\TailingReport;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CloseCase;
use Illuminate\Support\Facades\Storage;
class TailingReportDataTable extends DataTable
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
    //         ->editColumn('kasus', function ($data) {
    //             return Str::limit(strip_tags($data->case_name), 128, '');
    //         })
    //         ->editColumn('perilaku_tercatat', function ($data) {
        
    //             if($data->tailingPemahamanPerilaku != null && $data->tailingPemahamanPerilaku->isNotEmpty()) {
    //                 return Str::limit(strip_tags($data->tailingPemahamanPerilaku->first()->perilaku_tercatat), 128, '');
    //             }
    //             return Str::limit(strip_tags(""), 128, '');
                
    //         }) 
    //         ->editColumn('aktivitas_rutin', function ($data) {
    //             if($data->tailingPemahamanPerilaku != null && $data->tailingPemahamanPerilaku->isNotEmpty()){
    //                 return Str::limit(strip_tags($data->tailingPemahamanPerilaku->first()->aktivitas_rutin), 128, '');
    //             }
    //             return Str::limit(strip_tags(""), 128, '');
    //         })
    //         ->editColumn('hubungan_sosial', function ($data) {
    //             if($data->tailingPemahamanPerilaku != null && $data->tailingPemahamanPerilaku->isNotEmpty()){
    //                 return Str::limit(strip_tags($data->tailingPemahamanPerilaku->first()->hubungan_sosial), 128, '');
    //             }
    //             return Str::limit(strip_tags(""), 128, '');
    //         })
    //         ->editColumn('prediksi_perilaku', function ($data) {
    //             if($data->tailingPemahamanPerilaku != null && $data->tailingPemahamanPerilaku->isNotEmpty()){
    //                 return Str::limit(strip_tags($data->tailingPemahamanPerilaku->first()->prediksi_perilaku), 128, '');
    //             }
    //             return Str::limit(strip_tags(""), 128, '');
    //         })

    //          ->editColumn('pemahaman_perilaku_video_upload', function ($data) {
    //             $link = route('close.tailing.report.download-file', encrypt($data->id));

    //             return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
    //         })

            
    //         ->rawColumns([
    //             'pemahaman_perilaku_video_upload',
    //             'action'
    //         ]);
    // }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('target_photo', function ($row) {
                $folderName = $row->nama_satker;
                $folderName = strtolower(trim($row->nama_satker));
                $folderName = str_replace(" ","_", $folderName);
                $folderPath = asset('assets/images/placeholder.jpeg');
                
                if ($row->target_photo) {
                    $decoded = json_decode($row->target_photo);
                    if(count($decoded) > 0){
                        $imagePaths = $decoded;
                       //  $folderPath = asset('close_case_target_image/'. $folderName. '/'. $imagePaths[0]);
                       $folderPath =Storage::url('close/case/' . $imagePaths[0]);
                   }
                    
                } 
        
                // Tampilkan gambar dengan tag HTML
                return '<img src="' . $folderPath . '" alt="Foto Target" class="img-thumbnail" width="50" height="50">';
            }) 
            ->editColumn('satker', function ($data) {
                return Str::limit(strip_tags($data->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case_name), 128, '');
            })
            
            ->editColumn('upload_hasil_yang_dicapai', function ($data) {
                return view('backoffice.close.tailing.report.action', compact('data'))->render();
            })
            ->rawColumns([
                'target_photo',
                'upload_hasil_yang_dicapai',
            ]);
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Research\TailingReport $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function query(TailingReport $model)
    // {
    //     $user = auth()->user();
    //     $satker = $user->satker;
    //     $kodeSatker = $satker->kode_satker;
    //     return $model->newQuery()
    //         ->when(
    //             !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])
    //             // function($q) use ($user, $satker, $kodeSatker) {
    //             //     $q->where('tailing_pemahaman_perilaku.kode_satker', 'like', "$kodeSatker%");
    //             // }
    //         );
    // }
    public function query(CloseCase $model)
     {
         $user = auth()->user();
         $satker = $user->satker;
         $idSatker = $satker->id_satker;
        
         if($user->user_roles == "superadmin"){
            return $model->newQuery()
                         ->join('master_satker',DB::raw("CAST(close_case.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
                         ->join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
                        //  ->where('case_close_progresses.delineation_skenario_relasi', '1')
                         
                         ->orderBy('master_satker.nama_satker')
                         ->select(
                             'close_case.*',
                             'master_satker.nama_satker',
                         )->orderBy('created_at', 'desc');;

         }else{
            return $model->newQuery()
                         ->join('master_satker',DB::raw("CAST(close_case.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
                         ->join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
                        //  ->where('case_close_progresses.delineation_skenario_relasi', '1')
                         
                         ->when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                             $q->where('close_case.satker_id', '=', "$idSatker");
                         })
                         ->orderBy('master_satker.nama_satker')
                         ->select(
                             'close_case.*',
                             'master_satker.nama_satker',
                         )->orderBy('created_at', 'desc');;

         }
         
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
                'url' => route('close.tailing.report.index')
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
    // protected function getColumns()
    // {
    //     return [
    //         Column::make('DT_RowIndex')
    //             ->title('NO ')
    //             ->orderable(false)
    //             ->searchable(false)
    //             ->className('text-center')
    //             ->footer('NO'),

    //         Column::make('satker')
    //             ->name('satker')
    //             ->title('SATUAN KERJA')
    //             ->className('text-left')
    //             ->footer('SATUAN KERJA'),

    //         Column::make('kasus')
    //             ->name('kasus')
    //             ->title('KASUS')
    //             ->className('text-left')
    //             ->footer('KASUS'),

    //         Column::make('target_name')
    //             ->name('target_name')
    //             ->title('NAMA TARGET')
    //             ->className('text-left')
    //             ->footer('NAMA TARGET'),

    //         Column::make('target_identity_number')
    //             ->name('target_identity_number')
    //             ->title('NOMOR IDENTITAS')
    //             ->className('text-left')
    //             ->footer('NOMOR IDENTITAS'),

    //         Column::make('perilaku_tercatat')
    //             ->name('perilaku_tercatat')
    //             ->title('PERILAKU TERCATAT')
    //             ->className('text-left')
    //             ->footer('PERILAKU TERCATAT'),

    //         Column::make('aktivitas_rutin')
    //             ->name('aktivitas_rutin')
    //             ->title('AKTIVITAS RUTIN')
    //             ->className('text-left')
    //             ->footer('AKTIVITAS RUTIN'),

    //         Column::make('pemahaman_perilaku_video_upload')
    //             ->name('pemahaman_perilaku_video_upload')
    //             ->title('FILE')
    //             ->className('text-center')
    //             ->footer('FILE'),

    //     ];
    // }

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
                ->title('KASUS')
                ->className('text-left')
                ->footer('kasus'),

            Column::make('case_date')
                ->name('close_case.case_date')
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

            Column::make('upload_hasil_yang_dicapai')
                ->name('upload_hasil_yang_dicapai')
                ->title('UNDUH REPORT')
                ->className('text-left')
                ->footer('UNDUH REPORT'),

            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'tailing_pemahaman_perilaku-' . date('YmdHis');
    }
}
