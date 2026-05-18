<?php

namespace App\DataTables\Research;

use App\Models\Open\Research\ResearchSaranTindakLanjut;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ResearchSaranTLDataTable extends DataTable
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
            ->editColumn('nama_satker', function ($data) {
                
                return Str::limit(strip_tags($data
                    ->case
                    ->satker
                    ->nama_satker), 128, '');
            })
            ->editColumn('researchLaporanInformasiKhusus.researchSuratPerintah.case.nama_kasus', function ($data) {
                return Str::limit(strip_tags($data
                    ->case
                    ->nama_kasus), 128, '');
            })
            ->editColumn('researchLaporanInformasiKhusus.researchSuratPerintah.surat_perintah_number', function ($data) {
                if($data->researchSuratPerintah){
                    return Str::limit(strip_tags($data->researchSuratPerintah->surat_perintah_number), 128, '');
                }
                return '';
               
            })
            ->editColumn('researchLaporanInformasiKhusus.nomor_surat', function ($data) {
                if($data->researchLaporanInformasiKhusus){
                    return Str::limit(strip_tags($data->researchLaporanInformasiKhusus->nomor_surat), 128, '');
                }
                return '';
            })
            ->editColumn('tanggal_tl', function ($data) {
                return $data->saran_dan_tindak_lanjut_date->isoFormat('DD-MM-YYYY');
            })
            ->editColumn('saran_dan_tindak_lanjut', function ($data) {
                return Str::limit(strip_tags($data->saran_dan_tindak_lanjut), 128, '');
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.research.saran_tl.action', compact('data'))->render();
            })
            ->rawColumns([
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Open\Research\ResearchSaranTindakLanjut $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ResearchSaranTindakLanjut $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;

        return $model->newQuery()
            ->with([
                'researchLaporanInformasiKhusus',
                'researchLaporanInformasiKhusus.researchSuratPerintah', 
                'researchLaporanInformasiKhusus.researchSuratPerintah.case',
                'researchLaporanInformasiKhusus.researchSuratPerintah.case.satker'
            ])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($idSatker) {
                    $q->where(function ($query) use ($idSatker) {
                        $query->whereHas('researchLaporanInformasiKhusus.case', function ($caseQuery) use ($idSatker) {
                            $caseQuery->where('id_satker', $idSatker);
                        })
                        ->orWhereHas('researchLaporanInformasiKhusus.case.satker', function ($satkerQuery) use ($idSatker) {
                            $satkerQuery->where('parent_id', $idSatker);
                        });
                    });
                }
            )
            ->orderby('research_saran_dan_tindak_lanjut.created_at','DESC');
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
                'url' => route('open.research.advice-measure.index')
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

            Column::make('nama_satker')
                ->name('researchLaporanInformasiKhusus.researchSuratPerintah.case.satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('researchLaporanInformasiKhusus.researchSuratPerintah.case.nama_kasus')
                ->name('researchLaporanInformasiKhusus.researchSuratPerintah.case.nama_kasus')
                ->title('KASUS')
                ->className('text-left')
                ->footer('KASUS'),

            Column::make('researchLaporanInformasiKhusus.researchSuratPerintah.surat_perintah_number')
                ->name('researchLaporanInformasiKhusus.researchSuratPerintah.surat_perintah_number')
                ->title('NO. SURAT PERINTAH')
                ->className('text-left')
                ->footer('NO. SURAT PERINTAH'),

            Column::make('researchLaporanInformasiKhusus.nomor_surat')
                ->name('researchLaporanInformasiKhusus.nomor_surat')
                ->title('NO. LAPINSUS')
                ->className('text-left')
                ->footer('NO. LAPINSUS'),

            Column::make('tanggal_tl')
                ->name('saran_dan_tindak_lanjut_date')
                ->title('TGL. TINDAK LANJUT')
                ->className('text-left')
                ->footer('TGL. TINDAK LANJUT'),

            Column::make('saran_dan_tindak_lanjut')
                ->name('saran_dan_tindak_lanjut')
                ->title('SARAN TINDAK LANJUT')
                ->className('text-left')
                ->footer('SARAN TINDAK LANJUT'),

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
        return 'penelitian_saran_dan_tindak_lanjut-' . date('YmdHis');
    }
}
