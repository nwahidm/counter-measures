<?php

namespace App\DataTables\Interview;

use App\Models\Interview\InterviewJadwal;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InterviewJadwalDataTable extends DataTable
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
                return Str::limit(strip_tags($data->interviewer_name), 128, '');
            })
            ->editColumn('interviewer_schedule', function ($data) {
 
                if ($data->interviewer_schedule) {
                    $formattedDate = \Carbon\Carbon::parse($data->interviewer_schedule)->isoFormat('DD MMMM YYYY');
                    return $formattedDate;
                }
                return '';
            })
            ->editColumn('source_person_name', function ($data) {
                return Str::limit(strip_tags($data->source_person_name), 128, '');
            })
            ->editColumn('target_identity_number', function ($data) {
                $idType = $data->target_type_identity_number;

                return $idType . ': ' . Str::limit(strip_tags($data->target_identity_number), 128, '');
            })
            ->editColumn('target_gender', function ($data) {
                return Str::limit(strip_tags($data->target_gender), 128, '');
            })
            ->editColumn('target_religion', function ($data) {
                return Str::limit(strip_tags($data->target_religion), 128, '');
            })
            ->editColumn('target_occupation', function ($data) {
                return Str::limit(strip_tags($data->target_occupation), 128, '');
            })
            ->editColumn('target_education', function ($data) {
                return Str::limit(strip_tags($data->target_education), 128, '');
            })
            ->editColumn('target_photo', function ($data) {
                if ($data->target_photo) {
                    return '<img class="img-thumbnail" src="' . asset('storage/' . $data->target_photo) . '" style="width: 64px;">';
                }

                return '<span class="badge bg-danger text-white">Tidak ada foto</span>';
            })
            ->editColumn('action', function ($data) {
                return view('backoffice.open.interview.jadwal.action', compact('data'))->render();
            })
            ->rawColumns([
                'target_photo',
                'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Interview\InterviewJadwal $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InterviewJadwal $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        return $model->newQuery()
            ->with(['case','satker'])
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($user, $satker, $idSatker) {
                    $q->where('interview_jadwal.satker_id', $idSatker);
                }
            )
            // ->join('case_progresses','interview_jadwal.case_id','case_progresses.case_id')
            ->orderby('interview_jadwal.created_at','DESC');
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
                'url' => route('open.interview.jadwal.index')
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
                ->name('interviewer_name')
                ->title('NAMA PEWAWANCARA')
                ->className('text-left')
                ->footer('NAMA PEWAWANCARA'),

            Column::make('interviewer_schedule')
                ->name('interviewer_schedule')
                ->title('JADWAL PEWAWANCARA')
                ->className('text-left')
                ->footer('JADWAL PEWAWANCARA'),

            Column::make('source_person_name')
                ->name('source_person_name')
                ->title('NAMA DIWAWANCARA')
                ->className('text-center')
                ->footer('NAMA DIWAWANCARA'),

            Column::make('target_identity_number')
                ->name('target_identity_number')
                ->title('NO. IDENTITAS TARGET')
                ->className('text-center')
                ->footer('NO. IDENTITAS TARGET'),

            Column::make('target_gender')
                ->name('target_gender')
                ->title('JENIS KELAMIN TARGET')
                ->className('text-center')
                ->footer('JENIS KELAMIN TARGET'),

            Column::make('target_religion')
                ->name('target_religion')
                ->title('AGAMA TARGET')
                ->className('text-center')
                ->footer('AGAMA TARGET'),

            Column::make('target_occupation')
                ->name('target_occupation')
                ->title('PEKERJAAN TARGET')
                ->className('text-center')
                ->footer('PEKERJAAN TARGET'),

            Column::make('target_education')
                ->name('target_education')
                ->title('PENDIDIKAN TARGET')
                ->className('text-center')
                ->footer('PENDIDIKAN TARGET'),

            Column::make('target_photo')
                ->name('target_photo')
                ->title('FOTO TARGET')
                ->className('text-center')
                ->footer('FOTO TARGET'),

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
        return 'interview_jadwal-' . date('YmdHis');
    }
}
