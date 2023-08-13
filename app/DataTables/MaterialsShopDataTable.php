<?php

namespace App\DataTables;

use App\Models\MaterialsShop;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MaterialsShopDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'materials_shops.datatables_actions')
            ->addColumn('status', function ($data) {
                return $data->display_status;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MaterialsShop $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(MaterialsShop $model)
    {
        $shop      = $this->request->get('shop');
        $materials = $this->request->get('materials');

        $query = $model->newQuery()->with(['materials', 'shop'])->select('materials_shops.*');

        if (isset($shop)) {
            $query = $query->whereRelation('shop', 'name', $shop);
        }

        if (isset($materials)) {
            $query = $query->whereRelation('materials', 'name', $materials);
        }

        return $query;
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
            ->setTableId('materials-shops-table')
            ->minifiedAjax()
            ->addAction(['title' => '動作', 'width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => "<'row'<'col-sm-9 text-left'B><'col-sm-3'f>>" .
                "<'row'<'col-sm-12'tr>>" .
                "<'row'<'col-sm-5 text-left'i><'col-sm-7'p>>",
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'language'  => ['url' => '//cdn.datatables.net/plug-ins/1.13.1/i18n/zh-HANT.json',
                ],
                'buttons'   => [
                    ['extend' => 'excel', 'className' => 'btn btn-default btn-sm no-corner'],
                    ['extend' => 'csv', 'className' => 'btn btn-default btn-sm no-corner'],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner'],

                ],
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
            'id',
            'materials_uuid' => new Column([
                'title' => '材料', 'data' => 'materials.name', 'name' => 'materials.name',
            ]),
            'shops_uuid'     => new Column([
                'title' => '材料店', 'data' => 'shop.name', 'name' => 'shop.name',
            ]),
            'group_price' => ['title' => '團價'],
            'group_qty' => ['title' => '群組數量'],
            'status' => ['title' => '狀態'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'materials_shops_datatable_' . time();
    }
}
