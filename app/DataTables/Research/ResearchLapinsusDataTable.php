<?php

namespace App\DataTables\Research;

use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Open\Research\ResearchSuratPerintah;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ResearchLapinsusDataTable extends DataTable
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
            ->editColumn('researchSuratPerintah.case.satker.nama_satker', function ($data) {
                return Str::limit(strip_tags($data->case->satker->nama_satker), 128, '');
            })
            ->editColumn('researchSuratPerintah.case.nama_kasus', function ($data) {
                return Str::limit(strip_tags($data->case->nama_kasus), 128, '');
            })
            ->editColumn('researchSuratPerintah.surat_perintah_number', function ($data) {
                if($data->researchSuratPerintah){
                    return Str::limit(strip_tags($data->researchSuratPerintah->surat_perintah_number), 128, '');
                }
                return '';
            })
            ->editColumn('nomor_surat', function ($data) {
                return Str::limit(strip_tags($data->nomor_surat), 128, '');
            })
            ->editColumn('perihal_surat', function ($data) {
                return Str::limit(strip_tags($data->perihal_surat), 128, '');
            })
            ->editColumn('tanggal_surat', function ($data) {
                return optional($data->tanggal_surat)->isoFormat('DD MMMM YYYY') ?? '';
            })
            ->editColumn('download_lapinsus', function ($data) {
                $link = route('open.research.report.download-lapinsus',$data->id);
    
                if ($link) {
                    return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
                }
                return '<h6>Belum Isi Lapinsus</h6>';
                
            })
            ->editColumn('saran_tindak_button', function ($data) {
                $link = route('open.research.saran_tl.createfromlapinsus', $data->id);
                return '<a href="'.$link .'" class="btn btn-primary btn-icon btn-sm"
                data-toggle="tooltip"
                data-placement="top"><i class="bi bi-building-add text-white"></i></a>';
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.research.lapinsus.action', compact('data'))->render();
            })
            ->rawColumns([
                'upload_lapinsus',
                'download_lapinsus',
                'saran_tindak_button',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Research\ResearchLaporanInformasiKhusus $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ResearchLaporanInformasiKhusus $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;

        return $model->newQuery()
            ->with([
                'researchSuratPerintah', 
                'researchSuratPerintah.case',
                'researchSuratPerintah.case.satker',
                'ResearchSuratPerintah.case'
            ])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($idSatker) {
                    $q->where(function ($query) use ($idSatker) {
                        $query->whereHas('researchSuratPerintah.case', function ($caseQuery) use ($idSatker) {
                            $caseQuery->where('id_satker', $idSatker);
                        })
                        ->orWhereHas('researchSuratPerintah.case.satker', function ($satkerQuery) use ($idSatker) {
                            $satkerQuery->where('parent_id', $idSatker);
                        });
                    });
                }
            )
            ->orderby('research_laporan_informasi_khusus.created_at','DESC');
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
                'url' => route('open.research.spesific-intel-report.index')
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

            Column::make('researchSuratPerintah.case.satker.nama_satker')
                ->name('researchSuratPerintah.case.satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('researchSuratPerintah.case.nama_kasus')
                ->name('researchSuratPerintah.case.nama_kasus')
                ->title('KASUS')
                ->className('text-left')
                ->footer('KASUS'),

            Column::make('researchSuratPerintah.surat_perintah_number')
                ->name('researchSuratPerintah.surat_perintah_number')
                ->title('NO. SURAT PERINTAH')
                ->className('text-left')
                ->footer('NO. SURAT PERINTAH'),

            Column::make('nomor_surat')
                ->name('nomor_surat')
                ->title('NOMOR SURAT')
                ->className('text-left')
                ->footer('NOMOR SURAT'),

            Column::make('perihal_surat')
                ->name('perihal_surat')
                ->title('PERIHAL SURAT')
                ->className('text-left')
                ->footer('PERIHAL SURAT'),

            Column::make('tanggal_surat')
                ->name('tanggal_surat')
                ->title('TGL. SURAT')
                ->className('text-left')
                ->footer('TGL. SURAT'),

         
            Column::make('download_lapinsus')
                ->name('download_lapinsus')
                ->title('UNDUH REPORT LAPINSUS')
                ->className('text-center')
                ->footer('UNDUH REPORT LAPINSUS')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('saran_tindak_button')
                ->name('saran_tindak_button')
                ->title('SARAN TINDAK')
                ->className('text-center')
                ->footer('SARAN TINDAK'),

            Column::make('action')
                ->title('AKSI')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
                ->footer('AKSI'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'research_laporan_informasi_khusus-' . date('YmdHis');
    }
}
