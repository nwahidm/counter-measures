<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\LazyDataTablesExportHandler;

class UserDataTable extends DataTable
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
            ->addColumn('action', function ($data) {
                return view('backoffice.user.action', compact('data'))->render();
            })
            ->addColumn('role', function ($data) {
                return badgeRole($data->role);
            })
            ->editColumn('is_active', function($data) {
                if ($data->is_active) {
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->rawColumns(['action', 'role']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $user = auth()->user();

        $query = $model->newQuery()
                        ->when(!$user->hasRole(['superadmin', 'admin-kejagung']), function($q) use ($user) {
                            $q->where('users.id_satker', $user->id_satker);
                        })
                        ->leftJoin('master_satker', 'master_satker.id_satker', '=', 'users.id_satker')
                        ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                        ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->select(['users.id as id', 'users.email as email', 'users.name as name', 'users.is_active', 'roles.name as role', 'master_satker.nama_satker as nama_satker']);
        return $query;
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
                'url' => route('user.index')
            ])
            ->buttons(
                Button::make('reset')->className('btn-light btn-sm')
            )
            ->dom($domOption)
            ->parameters([
                'initComplete' => "function () {
                                var r = $('#user-table tfoot tr');
                                $('#user-table thead').append(r);
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
                ->title('No')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('No'),
            Column::make('nama_satker')
                ->name('master_satker.nama_satker')
                ->title('Satker')
                ->className('text-center')
                ->footer('Satker'),
            Column::make('name')
                ->name('users.name')
                ->title('Nama')
                ->className('text-center')
                ->footer('Nama'),
            Column::make('email')
                ->name('users.email')
                ->title('Email')
                ->className('text-center')
                ->footer('Email'),
            Column::make('role')
                ->name('roles.name')
                ->title('Role')
                ->className('text-center')
                ->footer('Role'),
            Column::make('is_active')
                ->name('users.is_active')
                ->title('Status')
                ->className('text-center')
                ->footer('Status'),
            Column::make('action')
                ->title('Aksi')
                ->className('text-center')
                ->footer('Aksi')
                ->width(110)
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
