<?php

namespace App\DataTables;

use App\Models\Assets;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class AssetsDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'assets.datatables_actions')
        ->addColumn('status', function ($data) {
                return $data->display_status;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Assets $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Assets $model)
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
            'assets_uuid' =>[
                'title' => '資源 Uuid'
            ],
            'module_uuid' =>[
                'title' => '模組 Uuid'
            ],
            'type' =>[
                'title' => '類型'
            ],
            'file_size' =>[
                'title' => '文件大小'
            ],
            'status' =>[
                'title' => '狀態'
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
        return 'assets_datatable_' . time();
    }
}
