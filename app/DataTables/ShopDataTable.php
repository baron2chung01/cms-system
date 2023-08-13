<?php

namespace App\DataTables;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ShopDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'shops.datatables_actions')
            ->editColumn('status', function ($data) {
                return Shop::DISP_STATUS[$data->status];
            })
            ->addColumn('regions', function ($data) {
                return Shop::find($data->id)->regions->pluck('name')->implode(', ');
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Shop $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Shop $model)
    {
        $query = $model->newQuery()->with(['category', 'regions'])->select('shops.*');
        $category = $this->request->get('category');
        $status = $this->request->get('status');

        if (isset($category)) {
            $query = $query->whereRelation('category', 'name', $category);
        }

        if (isset($status)) {
            $query = $query->where('status', $status);
        }

        if (User::find(auth()->id())->hasRole('partner')) {
            $query = $query->whereRelation('users', 'users.id', auth()->id());
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
            ->setTableId('shops-table')
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
            'category'       => new Column([
                'title' => '類別', 'data' => 'category.name', 'name' => 'category.name',
            ]),
            'name'           => [
                'title' => '名稱',
            ],
            'regions'        => [
                'title' => '地區', 'orderable' => false, 'searchable' => false,
            ],
            'shops_code'     => [
                'title' => '店鋪編號',
            ],
            'phone'          => [
                'title' => '電話',
            ],
            'contact_person' => [
                'title' => '聯繫人',
            ],
            'status'         => [
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
        return 'shops_datatable_' . time();
    }
}
