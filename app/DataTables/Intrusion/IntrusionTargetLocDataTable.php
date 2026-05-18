<?php

namespace App\DataTables\Intrusion;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;
use App\Models\Intrusion\IntrusionTargetLoc;

class IntrusionTargetLocDataTable extends DataTable
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
            ->editColumn('close_case.case_name', function ($data) {
                return Str::limit(strip_tags($data->case?->case_name), 128, '');
            })
            ->editColumn('target_name', function ($data) {
                return Str::limit(strip_tags($data->target_name), 128, '');
            })
            ->editColumn('target_gender', function ($data) {
                return Str::limit(strip_tags($data->target_gender), 128, '');
            })
            ->editColumn('lokasi_target', function ($data) {
                return Str::limit(strip_tags($data->lokasi_target), 128, '');
            })
            ->editColumn('upload_lokasi', function ($data) {
                if (!$data->lokasi_target_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.intrusion.target-loc.download-file', encrypt($data->lokasi_target_upload));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            ->addColumn('foto_target', function ($row) {
                $folderPath = asset('assets/images/placeholder.jpeg');
                
                if ($row->target_photo) {
                    $decoded = json_decode($row->target_photo);
                    if(count($decoded) > 0){
                        $imagePaths = $decoded;
                        $folderPath = asset('storage/' . $imagePaths[0]);
                    }
                    
                } 
        
                // Tampilkan gambar dengan tag HTML
                return '<img src="' . $folderPath . '" alt="Foto Target" class="img-thumbnail" width="50" height="50">';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.intrusion.target-loc.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.intrusion.target-loc.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.intrusion.target-loc.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'upload_lokasi',
                'foto_target',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Research\IntrusionTargetLoc $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(IntrusionTargetLoc $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        
        return $model->newQuery()
                ->when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                    $q->where('intrusion_target_lokasi.satker_id', '=', $idSatker);
                })
                ->leftJoin('master_satker', DB::raw('master_satker.id_satker::text'), 'intrusion_target_lokasi.satker_id')
                ->leftJoin('close_case', 'close_case.id', 'intrusion_target_lokasi.case_id')
                ->leftJoin('documents', 'documents.relation_id', DB::raw('intrusion_target_lokasi.id::text'))
                ->select('intrusion_target_lokasi.*', 'documents.doc_status_remark')
                ->orderBy('created_at', 'desc');;
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
                'url' => route('close.intrusion.target-loc.index')
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
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('close_case.case_name')
                ->name('close_case.case_name')
                ->title('KASUS')
                ->className('text-left')
                ->footer('KASUS'),
            
            Column::make('target_name')
                ->name('target_name')
                ->title('NAMA TARGET')
                ->className('text-left')
                ->footer('NAMA TARGET'),

            Column::make('target_gender')
                ->name('target_gender')
                ->title('JENIS KELAMIN')
                ->className('text-left')
                ->footer('JENIS KELAMIN'),

            Column::make('lokasi_target')
                ->name('lokasi_target')
                ->title('LOKASI TARGET')
                ->className('text-left')
                ->footer('LOKASI TARGET'),

            Column::make('foto_target')
                ->name('foto_target')
                ->title('FOTO TARGET')
                ->className('text-center')
                ->footer('FOTO TARGET')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('upload_lokasi')
                ->name('upload_lokasi')
                ->title('FILE LOKASI TARGET')
                ->className('text-center')
                ->footer('FILE LOKASI TARGET')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('doc_status_remark')
                ->name('documents.doc_status_remark')
                ->title('ANALISIS DOKUMEN')
                ->className('text-center')
                ->footer('ANALISIS DOKUMEN'),

            Column::make('action')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('140px')
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
        return 'observation_surat_perintah-' . date('YmdHis');
    }
}
