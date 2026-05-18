<?php
namespace App\DataTables;

use App\Models\CloseCase;
use Illuminate\Support\Str;
use App\Models\ExplorationRencanaAksi;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use App\DataTables\LazyDataTablesExportHandler;
use Illuminate\Support\Facades\Storage;
class ExplorationReportDataTable extends DataTable
{
    // public function dataTable($query)
    // {
        // return datatables()
        //     ->eloquent($query)
        //     ->addIndexColumn()
        //     ->editColumn('satker', function ($data) {
        //         return Str::limit(strip_tags($data->nama_satker), 128, '');
        //     })
        //     ->editColumn('kasus', function ($data) {
        //         return Str::limit(strip_tags($data->case_name), 128, '');
        //     })
        //     ->editColumn('rencana_aksi_data', function ($data) {
        //         return Str::limit(strip_tags($data->case_description), 128, '');
        //     })
        //     ->editColumn('rencana_aksi_detail', function ($data) {
        //         return Str::limit(strip_tags($data->target_occupation), 128, '');
        //     })
        //     ->editColumn('action', function ($data) {
        //         return view('backoffice.close.exploration.report.action', compact('data'))->render();
        //     })
        //     ->rawColumns([
        //         'rencana_aksi_upload',
        //         'action'
        //     ]);
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
                return view('backoffice.close.exploration.report.action', compact('data'))->render();
            })
            ->rawColumns([
                'target_photo',
                'upload_hasil_yang_dicapai',
            ]);
    }


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
                        )->orderBy('created_at', 'desc');

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
        // $user = auth()->user();
        // $satker = $user->satker;
        // $idSatker = $satker->id_satker;
        // return $model->newQuery()
        //             ->select(
        //                 'exploration_rencana_aksi.id_exploration_rencana_aksi as id_exploration_rencana_aksi',
        //                 'exploration_rencana_aksi.rencana_aksi_data as rencana_aksi_data',
        //                 'exploration_rencana_aksi.rencana_aksi_detail as rencana_aksi_detail',
        //                 'exploration_rencana_aksi.rencana_aksi_upload',
        //                 'master_satker.nama_satker',
        //                 'close_case.id',
        //                 'close_case.case_description',
        //                 'close_case.target_occupation',
        //                 'close_case.target_education',
        //                 'close_case.target_address',
        //                 'close_case.target_name',
        //                 'close_case.case_name'
        //             )
        //             ->join('master_satker', DB::raw("CAST(exploration_rencana_aksi.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
        //             ->join('close_case', DB::raw("close_case.id::text"), '=', DB::raw("exploration_rencana_aksi.case_id::text"))
        //             ->when(
        //                 !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
        //                 function ($q) use ($idSatker) {
        //                     $q->where('exploration_rencana_aksi.satker_id', 'like', "$idSatker%");
        //                 }
        //             )
        //             ->orderBy('master_satker.nama_satker');
    }

    public function html()
    {
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('close.exploration.report')
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
    //             ->title('Nama Case ')
    //             ->className('text-left')
    //             ->footer('Nama Case'),

    //         Column::make('case_description')
    //             ->name('case_description')
    //             ->title('Deskripsi Kasus')
    //             ->className('text-left')
    //             ->footer('Deskripsi Kasus'),

    //         Column::make('target_occupation')
    //             ->name('target_occupation')
    //             ->title('Pekerjaan')
    //             ->className('text-left')
    //             ->footer('Pekerjaan'),

    //         Column::make('target_name')
    //             ->name('target_name')
    //             ->title('Nama Target')
    //             ->className('text-center')
    //             ->footer('Nama Target'),

    //         Column::make('action')
    //             ->title('Aksi')
    //             ->orderable(false)
    //             ->searchable(false)
    //             ->exportable(false)
    //             ->width('100px')
    //             ->footer('Aksi'),

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
                ->name('close_case.target_name')
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
                ->footer('UNDUH REPORT')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            
        ];
    }
    protected function filename(): string
    {
        return 'research_lapinsus-'. date('YmdHis');;
    }

}

