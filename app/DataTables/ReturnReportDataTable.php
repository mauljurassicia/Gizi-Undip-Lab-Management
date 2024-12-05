<?php

namespace App\DataTables;

use App\Models\ReturnReport;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class ReturnReportDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'return_reports.datatables_actions')
        ->editColumn('status', function ($data) {
            return $data->status == 'pending' ?
                '<span class="badge badge-warning">Pending</span>' : ($data->status == 'approved' ?
                    '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-danger">Rejected</span>');
        })
        ->addColumn('brokenEquipment', function ($data) {
            return $data->brokenEquipment->room->name . ' - ' . $data->brokenEquipment->equipment->name;
        })
        ->editColumn('return_date', function ($data) {
            return date('d F Y', strtotime($data->return_date));
        })
        ->editColumn('price', function ($data) { return 'Rp. ' . number_format($data->price, 0, ',', '.'); })->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ReturnReport $model)
    {
        return $model->newQuery()->with(['brokenEquipment', 'brokenEquipment.room', 'brokenEquipment.equipment']);
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
                'buttons' => [
                ],
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
        return [
            'brokenEquipment' => ['title' => 'Peralatan Rusak', 'data' => 'brokenEquipment', 'searchable' => false],
            'quantity' => ['title' => 'Jumlah', 'data' => 'quantity'],
            'price' => ['title' => 'Tunai', 'data' => 'price'],
            'return_date' => ['title' => 'Tanggal Pengembalian', 'data' => 'return_date'],
            'status',

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'return_reportsdatatable_' . time();
    }
}