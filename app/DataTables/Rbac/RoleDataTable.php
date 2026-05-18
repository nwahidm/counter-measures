<?php

namespace App\DataTables\Rbac;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\DataTables\LazyDataTablesExportHandler;

class RoleDataTable extends DataTable
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
            ->editColumn('action', function ($data) {
                return '<button onclick="deleteData(event)" data-id="'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="tooltip"
                            data-placement="top" title="Delete Role">
                            <i class="bi bi-trash-fill text-white"></i>
                        </button>';
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Role $model)
    {
        return $model->newQuery()->orderByDesc('created_at');
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
                'url' => route('manage-role.role.index')
            ])
            ->buttons([])
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
                ->title('No')
                ->orderable(false)
                ->searchable(false)
                ->className('text-center')
                ->footer('No'),

            Column::make('name')
                ->title('Role')
                ->footer('Role'),

            Column::make('guard_name')
                ->title('Guard')
                ->footer('Guard'),

            Column::make('action')
                ->title('Aksi')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
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
        return 'Role_' . date('YmdHis');
    }
}
