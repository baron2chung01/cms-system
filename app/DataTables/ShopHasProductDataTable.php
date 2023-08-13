<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\ShopHasProduct;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class ShopHasProductDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'shop_has_products.datatables_actions')
        ->editColumn('status', function ($data) {
                return ShopHasProduct::DISP_STATUS[$data->status];
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ShopHasProduct $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ShopHasProduct $model)
    {
        $shop = $this->request->get('shop');
        $status = $this->request->get('status');

        $query = $model->newQuery()->with(['shop', 'product'])->select('shops_has_products.*');

        if (isset($shop)) {
            $query = $query->whereRelation('shop', 'name', $shop);
        }

        if (isset($status)) {
            $query = $query->where('shops_has_products.status', $status);
        }

        if (User::find(auth()->id())->hasRole('partner')) {
            $query = $query->whereRelation('shop.users', 'users.id', auth()->id());
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
            ->setTableId('shops-has-products-table')
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
            'shop_id'    => new Column([
                'title' => '材料店', 'data' => 'shop.name', 'name' => 'shop.name',
            ]),
            'product_id' => new Column([
                'title' => '產品', 'data' => 'product.name', 'name' => 'product.name',
            ]),
            'status'        => [
                'title' => '狀態',
            ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'shops_has_products_datatable_' . time();
    }
}
