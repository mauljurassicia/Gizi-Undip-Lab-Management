<?php

namespace App\DataTables;

use App\Models\LogBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class LogBookDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'log_books.datatables_actions')->editColumn('type', function ($data) {
            return $data->type == 'in' ? '<span class="badge badge-success m-auto">Pinjam</span>' : '<span class="badge badge-danger m-auto">Kembali</span>';
        })
        ->editColumn('time', function ($data) {
            return Carbon::parse($data->time)->format('d M Y, H:i');
        })
        ->editColumn('logbookable_type', function ($data) {
            return $data->logbookable_type == 'App\Models\schedule' ? '<span class="badge badge-primary m-auto">Pinjam Ruang</span>' : '<span class="badge badge-primary m-auto">Pinjam Alat</span>';
        })
        ->rawColumns(['type', 'action', 'logbookable_type']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LogBook $model)
    {
        return $model->newQuery()->with(['userable', 'logbookable']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px'])
            ->parameters([
                'dom'     => 'Bfrtip',
                'order'   => [[0, 'desc']],
                'buttons' => [],
                'initComplete' => "function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var input = document.createElement(\"input\");
                        if($(column.header()).attr('title') !== 'Action'){
                            $(input).appendTo($(column.header()))
                            .on('keyup change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }
                    });
                }",
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        /** @var User $user */
        $user = Auth::user();
        return [
            'userable.name' => [
                'name' => 'userable_id',
                'title' => 'Pengguna',
                'data' => 'userable.name',
                'visible' => $user->hasRole('administrator') || $user->hasRole('laborant')
            ],
            'logbookable.name' => ['name' => 'logbookable_id', 'title' => 'Judul', 'data' => 'logbookable.name'],
            'logbookable_type' => ['name' => 'logbookable_type', 'title' => 'Tipe', 'data' => 'logbookable_type'],
            'type' => ['name' => 'type', 'title' => 'Status', 'data' => 'type', 'searchable' => false, 'orderable' => false],
            'time' => ['name' => 'time', 'title' => 'Waktu', 'data' => 'time', 'searchable' => false, 'orderable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'log_booksdatatable_' . time();
    }
}
