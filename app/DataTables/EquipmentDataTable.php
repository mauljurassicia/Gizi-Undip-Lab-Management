<?php

namespace App\DataTables;

use App\Models\Equipment;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class EquipmentDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'equipment.datatables_actions')
            ->editColumn('image', function ($data) {

                return '<img src="' . asset($data->image) . '" width="100px">';
            })
            ->editColumn('price', function ($data) {
                return 'Rp. ' . number_format($data->price, 0, ',', '.');
            })->rawColumns(['image', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Equipment $model)
    {
        return $model->newQuery();
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
                    'export',
                    'reset',
                    'reload',
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
            'name' => ['name' => 'name', 'title' => 'Name', 'data' => 'name'],
            'price' => ['name' => 'price', 'title' => 'Harga', 'data' => 'price'],
            'image' => ['name' => 'image', 'title' => 'Gambar', 'data' => 'image', 'searchable' => false, 'orderable' => false],
            'type' => ['name' => 'type', 'title' => 'Tipe', 'data' => 'type'],
            'unit_type' => ['name' => 'unit_type', 'title' => 'Satuan', 'data' => 'unit_type'],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'equipmentdatatable_' . time();
    }
}
