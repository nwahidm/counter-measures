<?php

namespace App\DataTables\Interview;

use App\Models\Interview\InterviewSaranTL;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InterviewSaranTLDataTable extends DataTable
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
                return Str::limit(strip_tags($data->case->satker->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case->nama_kasus), 128, '');
            })
            ->editColumn('interview', function ($data) {
                if($data->interviewJadwal){
                    $person1 = $data->interviewJadwal->interviewer_name;
                    $person2 = $data->interviewJadwal->source_person_name;

                    return $person1 . ' dengan ' . $person2;
                }
                return '';
            })
            ->editColumn('interviewer_schedule', function ($data) {
                if($data->interviewJadwal){
                    return $data->interviewJadwal->interviewer_schedule->isoFormat('DD-MM-YYYY');
                }
                return '';
            })
            ->editColumn('upload_dokumen_wawancara', function ($data) {
                if($data->interviewHasil){
                    if (!$data->interviewHasil->upload_dokumen_wawancara) {
                        return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                    }

                    $link = route('open.interview.hasil.download-dokumen-wawancara', encrypt($data->interviewHasil->upload_dokumen_wawancara));

                    return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
                }
                return '';
            })
            ->editColumn('upload_video_wawancara', function ($data) {
                if($data->interviewHasil){
                    if (!$data->interviewHasil->upload_video_wawancara) {
                        return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                    }

                    $link = route('open.interview.hasil.download-video-wawancara', encrypt($data->interviewHasil->upload_video_wawancara));

                    return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
                }
                return '';
            })
            ->editColumn('saran_dan_tindak_lanjut_date', function ($data) {
                return $data->saran_dan_tindak_lanjut_date->isoFormat('DD MMMM YYYY');
            })
            ->editColumn('saran_dan_tindak_lanjut', function ($data) {
                return Str::limit(strip_tags($data->saran_dan_tindak_lanjut), 128, '');
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.interview.saran_tl.action', compact('data'))->render();
            })
            ->rawColumns([
                'upload_dokumen_wawancara',
                'upload_video_wawancara',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Interview\InterviewSaranTL $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InterviewSaranTL $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        return $model->newQuery()
            ->with(['interviewHasil', 'interviewJadwal', 'case', 'satker'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($user, $satker, $idSatker) {
                    $q->where('interview_saran_dan_tindak_lanjut.satker_id', $idSatker);
                }
            )
        // ->join('case_progresses','interview_jadwal.case_id','case_progresses.case_id')
        ->orderby('interview_saran_dan_tindak_lanjut.created_at','DESC');
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
                'url' => route('open.interview.saran_tl.index')
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
                ->name('case.nama_kasus')
                ->title('KASUS')
                ->className('text-left')
                ->footer('kasus'),

            Column::make('interview')
                ->name('interviewJadwal.interviewer_name')
                ->title('PEWAWANCARA & DIWAWANCARA')
                ->className('text-left')
                ->footer('PEWAWANCARA & DIWAWANCARA'),

            Column::make('interviewer_schedule')
                ->name('interviewJadwal.interviewer_schedule')
                ->title('JADWAL WAWANCARA')
                ->className('text-left')
                ->footer('JADWAL WAWANCARA'),

            Column::make('upload_dokumen_wawancara')
                ->name('upload_dokumen_wawancara')
                ->title('FILE DOKUMEN WAWANCARA')
                ->className('text-center')
                ->footer('FILE DOKUMEN WAWANCARA')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('upload_video_wawancara')
                ->name('upload_video_wawancara')
                ->title('FILE VIDEO WAWANCARA')
                ->className('text-center')
                ->footer('FILE VIDEO WAWANCARA')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('saran_dan_tindak_lanjut_date')
                ->name('saran_dan_tindak_lanjut_date')
                ->title('TGL. SARAN TINDAK LANJUT')
                ->className('text-left')
                ->footer('TGL. SARAN TINDAK LANJUT'),

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
        return 'interview_saran_tl-' . date('YmdHis');
    }
}
