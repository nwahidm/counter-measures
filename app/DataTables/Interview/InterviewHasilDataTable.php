<?php

namespace App\DataTables\Interview;

use App\Models\Interview\InterviewHasil;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InterviewHasilDataTable extends DataTable
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
            ->editColumn('interviewer_name', function ($data) {
                if($data->interviewJadwal){
                    $person1 = $data->interviewJadwal?->interviewer_name;
                    $person2 = $data->interviewJadwal?->source_person_name;

                    return $person1 . ' dengan ' . $person2;

                }
                return '';
                
            })
            ->editColumn('interviewer_schedule', function ($data) {
                if($data->interviewJadwal){
                    if ($data->interviewJadwal->interviewer_schedule) {
                        $formattedDate = \Carbon\Carbon::parse($data->interviewJadwal->interviewer_schedule)->isoFormat('DD MMMM YYYY');
                        return $formattedDate;
                    }
                }
                return '';
            })
            /*->editColumn('hasil_interview', function ($data) {
                return Str::limit(strip_tags($data->hasil_interview), 128, '');
            })
            ->editColumn('video_interview', function ($data) {
                return Str::limit(strip_tags($data->video_interview), 128, '');
            })*/
            ->editColumn('upload_dokumen_wawancara', function ($data) {
                if (!$data->upload_dokumen_wawancara) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }
                $link = route('open.interview.hasil.download-dokumen-wawancara', encrypt($data->upload_dokumen_wawancara));
                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            ->editColumn('upload_video_wawancara', function ($data) {
                if (!$data->upload_video_wawancara) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }
                $link = route('open.interview.hasil.download-video-wawancara', encrypt($data->upload_video_wawancara));
                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-download"></i></a>';
            })
            ->editColumn('laporan_wawancara', function ($data) {
                $link = route('open.interview.report.download-wawancara', $data->id_interview_result);
                $link2 = route('open.interview.report.download-wawancara-word', $data->id_interview_result);
                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '"><i class="fas fa-file-pdf"></i></a> 
                | <a class="btn btn-sm btn-icon btn-dark" href="' . $link2 . '"><i class="fas fa-file-word"></i></a>
                ';
                // | <a class="btn btn-sm btn-icon btn-dark" href="' . $link2 . '"><i class="fas fa-file-word"></i></a>
            })
            ->editColumn('saran_tinjut_interview_button', function ($data) {
                $link = route('open.interview.saran_tl.createfrominterview', $data->id_interview_result);
                return '<a href="'.$link .'" class="btn btn-primary btn-icon btn-sm"
                data-toggle="tooltip"
                data-placement="top"><i class="bi bi-building-add text-white"></i></a>';
            })
            ->editColumn('analytics_document_status', function ($data) {
                return optional($data->documents)->doc_status_remark ?? '';
            })
            ->editColumn('analytics_video_status', function ($data) {
                return optional($data->videoDocuments)->doc_status_remark ?? '';
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.interview.hasil.action', compact('data'))->render();
            })
            ->rawColumns([
                'saran_tinjut_interview_button',
                'upload_dokumen_wawancara',
                'upload_video_wawancara',
                'laporan_wawancara',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Interview\InterviewHasil $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InterviewHasil $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        return $model->newQuery()
        ->with(['interviewJadwal', 'case', 'satker', 'documents', 'videoDocuments'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($user, $satker, $idSatker) {
                    $q->where('interview_hasil.satker_id', $idSatker);
                }
            )
            ->join('case_progresses','interview_hasil.case_id','case_progresses.case_id')
            ->orderby('interview_hasil.created_at','DESC');
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
                'url' => route('open.interview.hasil.index')
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

            Column::make('interviewer_name')
                ->name('interviewJadwal.interviewer_name')
                ->title('PEWAWANCARA & DIWAWANCARA')
                ->className('text-left')
                ->footer('PEWAWANCARA & DIWAWANCARA'),

            Column::make('interviewer_schedule')
                ->name('interviewer_schedule')
                ->title('JADWAL WAWANCARA')
                ->className('text-left')
                ->footer('JADWAL WAWANCARA'),

            Column::make('laporan_wawancara')
                ->name('laporan_wawancara')
                ->title('LAPORAN WAWANCARA')
                ->className('text-left')
                ->footer('LAPORAN WAWANCARA')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            /*Column::make('video_interview')
                ->name('video_interview')
                ->title('VIDEO INTERVIEW')
                ->className('text-left')
                ->footer('VIDEO INTERVIEW'),*/

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
            
            
            Column::make('saran_tinjut_interview_button')
                ->name('saran_tinjut_interview_button')
                ->title('SARAN TINJUT')
                ->className('text-center')
                ->footer('SARAN TINJUT')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('analytics_document_status')
                ->name('analytics_document_status')
                ->title('ANALISA DOKUMEN')
                ->className('text-center')
                ->footer('ANALISA DOKUMEN')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),
                
            Column::make('analytics_video_status')
                ->title('ANALISA VIDEO')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
                ->footer('ANALISA VIDEO'),

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
        return 'interview_hasil-' . date('YmdHis');
    }
}
