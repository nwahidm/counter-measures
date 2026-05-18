<?php

namespace App\DataTables\Tapping;

use App\Models\CloseCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TappingReportDataTable extends DataTable
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
            ->editColumn('satker.nama_satker', function ($data) {
                return Str::limit(strip_tags($data->satker?->nama_satker), 128, '');
            })
            ->editColumn('nama_kasus', function ($data) {
                return Str::limit(strip_tags($data->case_name), 128, '');
            })
            ->editColumn('tanggal_kasus', function ($data) {
                return $data->case_date?->isoFormat('DD MMMM YYYY');
            })
            ->editColumn('nama_target', function ($data) {
                return Str::limit(strip_tags($data->target_name), 128, '');
            })
            ->editColumn('foto_target', function ($row) {
                $folderName = $row->nama_satker;
                $folderName = strtolower(trim($row->nama_satker));
                $folderName = str_replace(" ", "_", $folderName);
                $folderPath = asset('assets/images/placeholder.jpeg');

                if ($row->target_photo) {
                    $decoded = json_decode($row->target_photo);
                    if (count($decoded) > 0) {
                        $imagePaths = $decoded;
                        $folderPath = Storage::url('close/case/' . $imagePaths[0]);
                    }
                }

                // Tampilkan gambar dengan tag HTML
                return '<img src="' . $folderPath . '" alt="Foto Target" class="img-thumbnail" width="50" height="50">';
            })
            ->editColumn('download_report', function ($data) {
                $link = route('close.tapping.report.download-file', encrypt($data->id));

                return '<a class="btn btn-sm btn-icon btn-dark" href="' . $link . '" target="_blank"><i class="fas fa-file-download"></i></a>';
            })
            /*->editColumn('action', function ($data) {
                return view('backoffice.close.tapping.report.action', compact('data'))->render();
            })*/
            ->rawColumns([
                'foto_target',
                'download_report',
                //'action'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CloseCase $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CloseCase $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;

        return $model->newQuery()
            ->with('satker')
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($user, $satker, $idSatker) {
                    $q->where('close_case.satker_id', $idSatker);
                }
            )->orderby('close_case.created_at','DESC');;
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
                'url' => route('close.tapping.report.index')
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

            Column::make('satker.nama_satker')
                ->name('satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('nama_kasus')
                ->name('case_name')
                ->title('KASUS')
                ->className('text-left')
                ->footer('KASUS'),

            Column::make('tanggal_kasus')
                ->name('case_date')
                ->title('TGL. KASUS')
                ->className('text-left')
                ->footer('TGL. KASUS'),

            Column::make('nama_target')
                ->name('target_name')
                ->title('NAMA TARGET')
                ->className('text-left')
                ->footer('NAMA TARGET'),

            Column::make('foto_target')
                ->name('foto_target')
                ->title('FOTO TARGET')
                ->className('text-left')
                ->footer('FOTO TARGET')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            Column::make('download_report')
                ->name('download_report')
                ->title('UNDUH REPORT')
                ->className('text-center')
                ->footer('UNDUH REPORT')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false),

            /*Column::make('action')
                ->title('AKSI')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
                ->footer('AKSI'),*/
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'tapping_report-' . date('YmdHis');
    }
}
