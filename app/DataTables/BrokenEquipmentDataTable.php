<?php

namespace App\DataTables;

use App\Models\BrokenEquipment;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class BrokenEquipmentDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'broken_equipments.datatables_actions')
        ->editColumn('broken_date', function ($data) {
            return date('d F Y', strtotime($data->broken_date));
        })
        ->editColumn('return_date', function ($data) {
            if($data->return_date == null){
                return '-';
            }
            return date('d F Y', strtotime($data->return_date));
        })
        ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BrokenEquipment $model)
    {
        return $model->newQuery()->with(['room', 'equipment', 'user']);
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
            'room.name' => ['name' => 'room_id', 'title' => 'Ruangan', 'data' => 'room.name'],
            'user.name' => ['name' => 'user_id', 'title' => 'Pengguna', 'data' => 'user.name'],
            'equipment.name' => ['name' => 'equipment_id', 'title' => 'Alat', 'data' => 'equipment.name'],
            'quantity' => ['name' => 'quantity', 'title' => 'Jumlah', 'data' => 'quantity'],
            'broken_date' => ['name' => 'broken_date', 'title' => 'Tgl. Rusak', 'data' => 'broken_date'],
            'return_date' => ['name' => 'return_date', 'title' => 'Tgl. Kembali', 'data' => 'return_date'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'broken_equipmentsdatatable_' . time();
    }
}