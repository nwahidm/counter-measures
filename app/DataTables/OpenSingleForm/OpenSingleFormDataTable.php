<?php

namespace App\DataTables\OpenSingleForm;

use App\Models\OpenCaseSingleForm;
use Illuminate\Support\Facades\DB;  // Import the DB facade
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OpenSingleFormDataTable extends DataTable
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
            
            ->editColumn('case_name', function ($data) {
                return Str::limit(strip_tags($data->case_name), 128, '');
            })
            ->editColumn('case_date', function ($data) {
                return Str::limit(strip_tags($data->case_date), 128, '');
            })
            ->editColumn('case_description', function ($data) {
                return Str::limit(strip_tags($data->case_description), 128, '');
            })
            ->editColumn('case_procedure_type', function ($data) {
                return Str::limit(strip_tags($data->open_procedure_type), 128, '');
            })
            ->editColumn('target_name', function ($data) {
                return Str::limit(strip_tags($data->target_name), 128, '');
            })
            ->editColumn('nama_satker', function ($data) {
                return Str::limit(strip_tags($data->satker?->nama_satker), 128, '');
            })
            // ->editColumn('nama_operasi_rahasia', function ($data) {
            //     return Str::limit(strip_tags($data->nama_operasi_rahasia), 128, '');
            // })
            // ->editColumn('dinamika_teramati', function ($data) {
            //     return Str::limit(strip_tags($data->dinamika_teramati), 128, '');
            // })
            // ->editColumn('tanggal_dinamika_teramati', function ($data) {
            //     return Str::limit(strip_tags($data->tanggal_dinamika_teramati), 128, '');
            // })
            // ->editColumn('deskripsi_dinamika_teramati', function ($data) {
            //     return Str::limit(strip_tags($data->deskripsi_dinamika_teramati), 128, '');
            // })
            // ->editColumn('dinamika_target_dokumen_upload', function ($data) {
            //     if (!$data->dinamika_target_dokumen_upload) {
            //         return '<span class="badge bg-danger text-white">Tidak ada file</span>';
            //     }
            
            //     $link = route('close.infiltration.target-dynamics.download-file', encrypt($data->dinamika_target_dokumen_upload));
            
            //     return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fa fa-file-download"></i></a>';
            // })
            // ->editColumn('dinamika_target_video_upload', function ($data) {
            //     if (!$data->dinamika_target_video_upload) {
            //         return '<span class="badge bg-danger text-white">Tidak ada file</span>';
            //     }

            //     $link = route('close.infiltration.target-dynamics.download-file', encrypt($data->dinamika_target_video_upload));

            //     return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            // })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.single-form.action', compact('data'))->render();
            })
            ->rawColumns([
                'dinamika_target_dokumen_upload',
                'dinamika_target_video_upload',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Delineation\DelineationInformationVerification $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OpenCaseSingleForm $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        if($user->user_roles == "superadmin"){
            return $model->newQuery()->orderby('open_case_single_form.created_at','DESC')->with('satker');
        }else{
            return $model->newQuery()->with('satker')->where('satker_id', $idSatker)->orderby('open_case_single_form.created_at','DESC');
           

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
                'url' => route('open.singleform.single-form.index')
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
                                        column.search($(this).val(), false, false, true).draw();
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

            Column::make('case_name')
                ->name('case_name')
                ->title('KASUS')
                ->className('text-left')
                ->footer('kasus'),

            Column::make('case_date')
                ->name('case_date')
                ->title('Tanggal Kasus')
                ->className('text-left')
                ->footer('tanggal kasus'),

            Column::make('case_description')
                ->name('case_description')
                ->title('Deskripsi Kasus')
                ->className('text-left')
                ->footer('deskripsi kasus'),

            Column::make('case_procedure_type')
                ->name('open_procedure_type')
                ->title('Prosedur Kasus')
                ->className('text-left')
                ->footer('deskripsi kasus'),

            Column::make('target_name')
                ->name('target_name')
                ->title('Nama Target')
                ->className('text-left')
                ->footer('nama target'),

            Column::make('action')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
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
        return 'research_lapinsus-' . date('YmdHis');
    }
}
