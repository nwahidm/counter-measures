<?php

namespace App\DataTables\Tailing;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;
use App\Models\Tailing\TailingPemahamanPerilaku;

class TailingPemahamanPerilakuDataTable extends DataTable
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
                return Str::limit(strip_tags($data->satker->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case->case_name), 128, '');
            })
            ->editColumn('perilaku_tercatat', function ($data) {
                return Str::limit(strip_tags($data->perilaku_tercatat), 128, '');
            }) 
            ->editColumn('aktivitas_rutin', function ($data) {
                return Str::limit(strip_tags($data->aktivitas_rutin), 128, '');
            })
            ->editColumn('hubungan_sosial', function ($data) {
                return Str::limit(strip_tags($data->hubungan_sosial), 128, '');
            })
            ->editColumn('prediksi_perilaku', function ($data) {
                return Str::limit(strip_tags($data->prediksi_perilaku), 128, '');
            })

             ->editColumn('pemahaman_perilaku_video_upload', function ($data) {
                if (!$data->pemahaman_perilaku_video_upload) {
                    return '<span class="badge bg-danger text-white">Tidak ada file</span>';
                }

                $link = route('close.tailing.pemahaman-perilaku.download-file', encrypt($data->pemahaman_perilaku_video_upload));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
            })

            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.tailing.pemahaman-perilaku.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.tailing.pemahaman-perilaku.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.tailing.pemahaman-perilaku.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'pemahaman_perilaku_video_upload',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Research\TailingPemahamanPerilaku $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TailingPemahamanPerilaku $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        return $model->newQuery()
            ->with(['case', 'satker', 'VideoDocuments'])
            ->join("video_documents", "video_documents.relation_id","=","tailing_pemahaman_perilaku.id")
            ->select(
                    "tailing_pemahaman_perilaku.*",
                    DB::raw("(CASE 
                                WHEN video_documents.doc_status = '8' THEN 'Error'
                                ELSE video_documents.doc_status_remark END) AS doc_status_remark"),
                    "video_documents.doc_status"
                )
            ->when(
                !$user->hasRole(["superadmin", "admin-kejagung", "jaksa-penegakkan-hukum"]),
                function($q) use ($user, $satker, $idSatker) {
                    $q->where("tailing_pemahaman_perilaku.id_satker", $idSatker);
                }
            )->orderBy('created_at', 'desc');
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
                'url' => route('close.tailing.pemahaman-perilaku.index')
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
                ->footer('KASUS'),

            Column::make('target_name')
                ->name('target_name')
                ->title('NAMA TARGET')
                ->className('text-left')
                ->footer('NAMA TARGET'),

            Column::make('target_identity_number')
                ->name('target_identity_number')
                ->title('NOMOR IDENTITAS')
                ->className('text-left')
                ->footer('NOMOR IDENTITAS'),

            Column::make('perilaku_tercatat')
                ->name('perilaku_tercatat')
                ->title('PERILAKU TERCATAT')
                ->className('text-left')
                ->footer('PERILAKU TERCATAT'),

            Column::make('aktivitas_rutin')
                ->name('aktivitas_rutin')
                ->title('AKTIVITAS RUTIN')
                ->className('text-left')
                ->footer('AKTIVITAS RUTIN'),

            Column::make('pemahaman_perilaku_video_upload')
                ->name('pemahaman_perilaku_video_upload')
                ->title('VIDEO')
                ->className('text-center')
                ->footer('VIDEO')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('doc_status_remark')
                ->name('VideoDocuments.doc_status_remark')
                ->title('STATUS')
                ->className('text-left')
                ->footer('STATUS'),

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
        return 'tailing_pemahaman_perilaku-' . date('YmdHis');
    }
}
