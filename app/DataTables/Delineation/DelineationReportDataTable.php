<?php

namespace App\DataTables\Delineation;

use App\Models\CloseCase;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\LazyDataTablesExportHandler;
use Illuminate\Support\Facades\Storage;

class DelineationReportDataTable extends DataTable
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
             ->addColumn('target_photo', function ($row) {
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
             ->editColumn('delineation_download_report_file', function ($data) {
                $link = route('close.delineation.report.download-file', $data->id);

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
             ->editColumn('action', function ($data) {
                 return view('backoffice.close.delineation.report.action', compact('data'))->render();
             })
             ->rawColumns(['action', 'target_photo', 'delineation_download_report_file']);
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
                 'url' => route('close.delineation.report.index')
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
            
            Column::make('delineation_download_report_file')
                 ->name('delineation_download_report_file')
                 ->title('FILE')
                 ->orderable(false)
                 ->searchable(false)
                 ->exportable(false)
                 ->className('text-center')
                 ->footer('FILE'),
 
            //  Column::make('action')
            //      ->title('Aksi')
            //      ->width(150)
            //      ->orderable(false)
            //      ->searchable(false)
            //      ->exportable(false)
            //      ->footer('Aksi'),
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
