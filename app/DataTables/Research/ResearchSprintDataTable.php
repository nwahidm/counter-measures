<?php

namespace App\DataTables\Research;

use App\Models\Open\Research\ResearchSuratPerintah;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ResearchSprintDataTable extends DataTable
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
            ->editColumn('case.satker.nama_satker', function ($data) {
                return Str::limit(strip_tags($data->case?->satker->nama_satker), 128, '');
            })
            ->editColumn('case.nama_kasus', function ($data) {
                return Str::limit(strip_tags($data->case?->nama_kasus), 128, '');
            })
            ->editColumn('nomor_sprint', function ($data) {
                return Str::limit(strip_tags($data->surat_perintah_number), 128, '');
            })
            ->editColumn('perihal_sprint', function ($data) {
                return Str::limit(strip_tags($data->surat_perintah_perihal), 128, '');
            })
            ->editColumn('tanggal_sprint', function ($data) {
                return $data->surat_perintah_date?->isoFormat('DD-MM-YYYY');
            })
            ->editColumn('tanggal_mulai_sprint', function ($data) {
                return $data->surat_perintah_date_started?->isoFormat('DD-MM-YYYY');
            })
            ->editColumn('tanggal_akhir_sprint', function ($data) {
                return $data->surat_perintah_date_finished?->isoFormat('DD-MM-YYYY');
            })
            ->editColumn('upload_sprint', function ($data) {
                if (!$data->surat_perintah_path) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('open.research.warrant.download-file', encrypt($data->surat_perintah_path));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            ->editColumn('action', function ($data) {
      
                return view('backoffice.open.research.sprint.action', compact('data'))->render();
            })
            ->rawColumns([
                'upload_sprint',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Open\Research\ResearchSuratPerintah $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ResearchSuratPerintah $model)
    {
        $user = auth()->user();
        $satker = $user?->satker;
        $idSatker = $satker?->satker_id;

        return $model->newQuery()
            ->with(['case', 'case.satker'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function($q) use ($user, $satker, $idSatker) {
                    $q->join('master_satker', 'research_surat_perintah.satker_id', '=', 'master_satker.id_satker')
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('research_surat_perintah.satker_id', '=', $user->id_satker)
                              ->orWhere('master_satker.parent_id', '=', $user->id_satker);
                        });
                }
            )
            // )->join('case_progresses','research_surat_perintah.case_id','case_progresses.case_id')
            ->orderby('research_surat_perintah.created_at','DESC');
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
                'url' => route('open.research.warrant.index')
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

            Column::make('case.satker.nama_satker')
                ->name('case.satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('case.nama_kasus')
                ->name('case.nama_kasus')
                ->title('KASUS')
                ->className('text-left')
                ->footer('NAMA KASUS'),

            Column::make('nomor_sprint')
                ->name('nomor_sprint')
                ->title('NO. SURAT PERINTAH')
                ->className('text-left')
                ->footer('NO. SURAT PERINTAH'),

            Column::make('perihal_sprint')
                ->name('perihal_sprint')
                ->title('PERIHAL SURAT PERINTAH')
                ->className('text-left')
                ->footer('PERIHAL SURAT PERINTAH'),

            Column::make('tanggal_sprint')
                ->name('tanggal_sprint')
                ->title('TGL. SURAT PERINTAH')
                ->className('text-center')
                ->footer('TGL. SURAT PERINTAH'),

            Column::make('tanggal_mulai_sprint')
                ->name('tanggal_mulai_sprint')
                ->title('TGL. MULAI SURAT PERINTAH')
                ->className('text-center')
                ->footer('TGL. MULAI SURAT PERINTAH'),

            Column::make('tanggal_akhir_sprint')
                ->name('tanggal_akhir_sprint')
                ->title('TGL. AKHIR SURAT PERINTAH')
                ->className('text-center')
                ->footer('TGL. AKHIR SURAT PERINTAH'),

            Column::make('upload_sprint')
                ->name('upload_sprint')
                ->title('FILE SURAT PERINTAH')
                ->className('text-center')
                ->footer('FILE SURAT PERINTAH'),

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
        return 'research_surat_perintah-' . date('YmdHis');
    }
}
