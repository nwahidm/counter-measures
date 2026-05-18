<?php

namespace App\DataTables\Tapping;

use App\Models\Tapping\TappingIntelligentSignal;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TappingIntelligentSignalDataTable extends DataTable
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
            ->editColumn('satker', function ($data) {
                return Str::limit(strip_tags($data->tappingElectronicDevice->case->satker->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->tappingElectronicDevice->case->case_name), 128, '');
            })
            ->editColumn('tanggal_penyadapan', function ($data) {
                return $data->tanggal_penyadapan?->isoFormat('DD-MM-YYYY');
            })
            ->editColumn('jenis_sinyal', function ($data) {
                return Str::limit(strip_tags($data->jenis_sinyal), 128, '');
            })
            ->editColumn('deskripsi_intelligent_signal', function ($data) {
                return Str::limit(strip_tags($data->deskripsi_hasil), 128, '');
            })
            ->editColumn('dokumen_upload', function ($data) {
                if (!$data->dokumen_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.tapping.intelligent_signal.download-dokumen', encrypt($data->dokumen_upload));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            ->editColumn('video_upload', function ($data) {
                if (!$data->video_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.tapping.intelligent_signal.download-video', encrypt($data->video_upload));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.tapping.intelligent_signal.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.tapping.intelligent_signal.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.tapping.intelligent_signal.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'dokumen_upload',
                'video_upload',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Tapping\TappingIntelligentSignal $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TappingIntelligentSignal $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        return $model->newQuery()
            ->with(['tappingElectronicDevice', 'case', 'satker'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($user, $satker, $idSatker) {
                    $q->where('tapping_intelligent_signal.satker_id', $idSatker);
                }
            )->orderby('tapping_intelligent_signal.created_at','DESC');;
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
                'url' => route('close.tapping.intelligent_signal.index')
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

            Column::make('satker')
                ->name('satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('kasus')
                ->name('case.case_name')
                ->title('KASUS')
                ->className('text-left')
                ->footer('kasus'),

            Column::make('tanggal_penyadapan')
                ->name('tanggal_penyadapan')
                ->title('TGL. PENYADAPAN')
                ->className('text-left')
                ->footer('TGL. PENYADAPAN'),

            Column::make('jenis_sinyal')
                ->name('jenis_sinyal')
                ->title('JENIS SINYAL')
                ->className('text-left')
                ->footer('JENIS SINYAL'),

            Column::make('deskripsi_intelligent_signal')
                ->name('deskripsi_hasil')
                ->title('DESKRIPSI HASIL')
                ->className('text-center')
                ->footer('DESKRIPSI HASIL'),

            Column::make('dokumen_upload')
                ->name('dokumen_upload')
                ->title('FILE DOKUMEN')
                ->className('text-center')
                ->footer('FILE DOKUMEN')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('video_upload')
                ->name('video_upload')
                ->title('FILE VIDEO')
                ->className('text-center')
                ->footer('FILE VIDEO')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

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
        return 'tapping_intelligent_signal-' . date('YmdHis');
    }
}
