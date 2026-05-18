<?php
namespace App\DataTables;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\ExplorationTargetIdentity;
use App\DataTables\LazyDataTablesExportHandler;

class ExplorationTargetIdentityDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('satker', function ($data) {
                return Str::limit(strip_tags($data->nama_satker), 128, '');
            })
            ->editColumn('kasus', function ($data) {
                return Str::limit(strip_tags($data->case_name), 128, '');
            })
            ->editColumn('target_name', function ($data) {
                return Str::limit(strip_tags($data->target_name), 128, '');
            })
            ->editColumn('target_gender', function ($data) {
                return Str::limit(strip_tags($data->target_gender), 128, '');
            })
            ->editColumn('target_religion', function ($data) {
                return Str::limit(strip_tags($data->target_religion), 128, '');
            })
            ->editColumn('target_education', function ($data) {
                return Str::limit(strip_tags($data->target_education), 128, '');
            })
            ->addColumn('target_photo', function ($data) {
                $folderPath = asset('assets/images/placeholder.jpeg');  // Default placeholder image
                
                if ($data->target_photo) {
                    $folderPath = Storage::url($data->target_photo);
                    // $decoded = json_decode($data->target_photo);
                    // if (is_array($decoded) && count($decoded) > 0) {
                    //     $imagePath = $decoded[0];  // Assuming the first item is the path
                    //     $folderPath = Storage::url($data->target_photo);
                    // }
                }
                
                return '<img src="' . $folderPath . '" alt="Foto Target" class="img-thumbnail" width="50" height="50">';
            })
            // ->editColumn('action', function ($data) {
            //     return view('backoffice.close.exploration.indentitastarget.action', compact('data'))->render();
            // })
            ->editColumn('action', function ($data) {
                if ($data->caseProgress != null && $data->caseProgress->percentage < 100) {
                    return view('backoffice.close.exploration.indentitastarget.action', compact('data'))->render();
                } else {
                    return view('backoffice.close.exploration.indentitastarget.action-completed', compact('data'))->render();
                }
                
            })
            ->rawColumns([
                'target_photo',
                'action'
            ]);
    }

    public function query(ExplorationTargetIdentity $model)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $idSatker = $satker->id_satker;
        return $model->newQuery()
                    ->select(
                        'exploration_target_identitas.*',
                        // 'exploration_target_identitas.target_name',
                        // 'exploration_target_identitas.target_identity_number',
                        // 'exploration_target_identitas.target_identity_number_type',
                        // 'exploration_target_identitas.target_gender',
                        // 'exploration_target_identitas.target_religion',
                        // 'exploration_target_identitas.target_occupation',
                        // 'exploration_target_identitas.target_education',
                        // 'exploration_target_identitas.target_photo',
                        'master_satker.nama_satker',
                        'close_case.case_name'
                    )
                    ->leftJoin('master_satker', DB::raw("CAST(exploration_target_identitas.satker_id AS bigint)"), '=', DB::raw("CAST(master_satker.id_satker AS bigint)"))
                    ->leftJoin('close_case', DB::raw("close_case.id::text"), '=', DB::raw("exploration_target_identitas.case_id::text"))
                    ->when(
                        !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                        function ($q) use ($idSatker) {
                            $q->where('exploration_target_identitas.satker_id', 'like', "$idSatker%");
                        }
                    )
                    ->orderBy('master_satker.nama_satker')
                    ->orderBy('exploration_target_identitas.created_at', 'desc');
    }

    public function html()
    {
        $domOption = "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6 pb-2'B>>
                          <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'url' => route('close.exploration.identitas-target.index')
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
            Column::make('DT_RowIndex')
                ->title('NO ')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('NO'),

            Column::make('satker')
                ->name('master_satker.nama_satker')
                ->title('SATUAN KERJA')
                ->className('text-left')
                ->footer('SATUAN KERJA'),

            Column::make('kasus')
                ->name('close_case.case_name')
                ->title('Nama Kasus ')
                ->className('text-left')
                ->footer('Nama Kasus'),

            Column::make('target_name')
                ->name('target_name')
                ->title('Nama')
                ->className('text-left')
                ->footer('Nama'),

            Column::make('target_gender')
                ->name('target_gender')
                ->title('Jenis Kelamin')
                ->className('text-left')
                ->footer('Jenis Kelamin'),
            Column::make('target_religion')
                ->name('target_religion')
                ->title('Agama')
                ->className('text-left')
                ->footer('Agama'),
            Column::make('target_education')
                ->name('target_education')
                ->title('Pendidikan')
                ->className('text-left')
                ->footer('Pendidikan'),

            Column::make('target_photo')
                ->name('target_photo')
                ->title('Foto')
                ->className('text-center')
                ->footer('Foto'),

            Column::make('action')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->width('100px')
                ->footer('Aksi'),

        ];
    }

    protected function filename(): string
    {
        return 'Identitas_Taget-'. date('YmdHis');;
    }

}

