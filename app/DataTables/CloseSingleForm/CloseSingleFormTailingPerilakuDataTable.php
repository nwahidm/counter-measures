<?php

namespace App\DataTables\CloseSingleForm;

use Illuminate\Support\Str;
use App\Models\VideoDocuments;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\CloseCaseSingleForm;
use Illuminate\Support\Facades\Log;
use App\Models\VideoDocumentAnalytics;
use Yajra\DataTables\Services\DataTable;

class CloseSingleFormTailingPerilakuDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('video_doc_note', function ($data) {
                return Str::limit(strip_tags($data->video_doc_note), 128, '');
            })
            ->editColumn('video_doc_analytic_2', function ($data) {
                return $data->video_doc_analytic_2;
            })
            ->editColumn('video_doc_summary_2', function ($data) {
                return $data->video_doc_summary_2;
            });
    }

    protected $data;

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function query(VideoDocumentAnalytics $model)
    {
        $id = $this->data?->relation_id_tailing_video_1;

        $video_data = VideoDocuments::where('relation_id',$id)->first();
    
        $query = $model->newQuery()->where(
            'video_doc_id', $video_data->id)->orderByRaw('CAST(substring(video_doc_note from 1 for position(\' - \' in video_doc_note) - 1) AS INTEGER) ASC');

        return $query;
    }

    public function html()
    {
        // $id = request()->route('pemahaman_perilaku'); // Correct parameter name
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
        
        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('close.singleform.single-form.video.tailing-perilaku', ['data_id' => $this->data->id])
            ])
            ->buttons(
                Button::make('excel')->className('btn-light btn-sm'),
                Button::make('reset')->className('btn-light btn-sm')
            )
            ->dom($domOption)
            ->parameters([
                'initComplete' => "function () {
                                var r = $('#data-table-tailing-perilaku tfoot tr');
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

    protected function getColumns()
    {
        return [
         
            Column::make('video_doc_note')
                ->name('video_doc_note')
                ->title('DURASI')
                ->className('text-left')
                ->footer('DURASI'),

            Column::make('video_doc_analytic_2')
                ->name('video_doc_analytic_2')
                ->title('Analisa Kegiatan Video')
                ->className('text-left')
                ->footer('Analisa Kegiatan Video'),

            Column::make('video_doc_summary_2')
                ->name('video_doc_summary_2')
                ->title('Kesimpulan Kegiatan Video')
                ->className('text-left')
                ->footer('Kesimpulan Kegiatan Video'),
        ];
    }

    protected function filename(): string
    {
        return 'tailing_pemahaman_perilaku-' . date('YmdHis');
    }
}
