<?php

namespace App\DataTables\Elicitation;

use App\Models\VideoDocumentAnalytics;
use App\Models\VideoDocuments;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ElicitationInterviewResultVideoShowDataTable extends DataTable
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
                return '<div style="text-align: justify;">' . $data->video_doc_analytic_2 . '</div>';
            })
            ->editColumn('video_doc_summary_2', function ($data) {
                return '<div style="text-align: justify;">' . $data->video_doc_summary_2 . '</div>';
            })->rawColumns([
                'video_doc_analytic_2',
                'video_doc_summary_2'
            ]);;
    }

    public function query(VideoDocumentAnalytics $model)
    {
        $id = request()->route('elicit_interview'); // Asumsi ID diterima dari route

        $video_data = VideoDocuments::where('relation_id',$id)->orderby('created_at','DESC')->first();
    

        $query = $model->newQuery()->where(
            'video_doc_id', $video_data->id)->orderByRaw('CAST(substring(video_doc_note from 1 for position(\' - \' in video_doc_note) - 1) AS INTEGER) ASC');

        
            

        return $query;
    }

    public function html()
    {
        $id = request()->route('elicit_interview'); // Correct parameter name
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
        
        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('open.data.elicit-interview.show', ['elicit_interview' => $id])
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
        return 'elicitation_interview_hasil-' . date('YmdHis');
    }
}

