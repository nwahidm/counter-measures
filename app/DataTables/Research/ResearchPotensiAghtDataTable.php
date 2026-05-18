<?php

namespace App\DataTables\Research;

use App\Models\Open\Research\ResearchPotensiAght;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ResearchPotensiAghtDataTable extends DataTable
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
            ->editColumn('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case.satker.nama_satker', function ($data) {
                return Str::limit(strip_tags($data
                    ->case
                    ->satker
                    ->nama_satker), 128, '');
            })
            ->editColumn('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case.nama_kasus', function ($data) {
                return Str::limit(strip_tags($data
                    ->case
                    ->nama_kasus), 128, '');
            })
            ->editColumn('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.surat_perintah_number', function ($data) {
                if($data->researchSuratPerintah){
                    return Str::limit(strip_tags($data
                        ->researchSuratPerintah
                        ->surat_perintah_number), 128, '');
                }
                return '';
            })
            ->editColumn('researchSaranTindakLanjut.researchLaporanInformasiKhusus.nomor_surat', function ($data) {
                if($data->researchLaporanInformasiKhusus){
                    return Str::limit(strip_tags($data
                        ->researchLaporanInformasiKhusus
                        ->nomor_surat), 128, '');
                }
                return '';
            })
            ->editColumn('researchSaranTindakLanjut.saran_dan_tindak_lanjut', function ($data) {
                if($data->researchSaranTindakLanjut){
                    return Str::limit(strip_tags($data->researchSaranTindakLanjut
                        ->saran_dan_tindak_lanjut), 128, '');
                }
                return '';
            })
            ->editColumn('ancaman', function ($data) {
                return Str::limit(ucwords(strip_tags($data->ancaman)), 128, '');
            })
            ->editColumn('gangguan', function ($data) {
                return Str::limit(ucwords(strip_tags($data->gangguan)), 128, '');
            })
            ->editColumn('hambatan', function ($data) {
                return Str::limit(strip_tags($data->hambatan), 128, '');
            })
            ->editColumn('tantangan', function ($data) {
                return Str::limit(strip_tags($data->tantangan), 128, '');
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.research.potensi_aght.action', compact('data'))->render();
            })
            ->rawColumns([
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Open\Research\ResearchPotensiAght $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ResearchPotensiAght $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        // $data1 = ResearchPotensiAght::get();

        return $model->newQuery()
            ->with([
                'researchSaranTindakLanjut',
                'researchSaranTindakLanjut.case',
                'researchSaranTindakLanjut.researchLaporanInformasiKhusus',
                'researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah', 
                'researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case',
                'researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case.satker'
            ])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($idSatker) {
                    $q->where(function ($query) use ($idSatker) {
                        $query->whereHas('researchSaranTindakLanjut.case', function ($caseQuery) use ($idSatker) {
                            $caseQuery->where('id_satker', $idSatker);
                        })
                        ->orWhereHas('researchSaranTindakLanjut.case.satker', function ($satkerQuery) use ($idSatker) {
                            $satkerQuery->where('parent_id', $idSatker);
                        });
                    });
                }
            )    
            ->orderby('research_potensi_aght.created_at','DESC');
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
                'url' => route('open.research.tibc.index')
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

            Column::make('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case.satker.nama_satker')
                ->name('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case.satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case.nama_kasus')
                ->name('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case.nama_kasus')
                ->title('KASUS')
                ->className('text-left')
                ->footer('kasus'),

            Column::make('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.surat_perintah_number')
                ->name('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.surat_perintah_number')
                ->title('NO. SURAT PERINTAH')
                ->className('text-left')
                ->footer('sprint'),

            Column::make('researchSaranTindakLanjut.researchLaporanInformasiKhusus.nomor_surat')
                ->name('researchSaranTindakLanjut.researchLaporanInformasiKhusus.nomor_surat')
                ->title('NO. LAPINSUS')
                ->className('text-left')
                ->footer('NO. LAPINSUS'),

            Column::make('researchSaranTindakLanjut.saran_dan_tindak_lanjut')
                ->name('researchSaranTindakLanjut.saran_dan_tindak_lanjut')
                ->title('SARAN TINDAK LANJUT')
                ->className('text-left')
                ->footer('SARAN TINDAK LANJUT'),

            Column::make('ancaman')
                ->name('ancaman')
                ->title('ANCAMAN')
                ->className('text-left')
                ->footer('ANCAMAN'),

            Column::make('gangguan')
                ->name('gangguan')
                ->title('GANGGUAN')
                ->className('text-left')
                ->footer('GANGGUAN'),

            Column::make('hambatan')
                ->name('hambatan')
                ->title('HAMBATAN')
                ->className('text-left')
                ->footer('HAMBATAN'),

            Column::make('tantangan')
                ->name('tantangan')
                ->title('TANTANGAN')
                ->className('text-left')
                ->footer('TANTANGAN'),

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
        return 'research_potensi_aght-' . date('YmdHis');
    }
}
