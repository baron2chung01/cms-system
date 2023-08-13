<?php

namespace App\DataTables;

use App\Models\RenovateCourse;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class RenovateCourseDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'renovate_courses.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\RenovateCourse $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(RenovateCourse $model)
    {
        return $model->newQuery()->with('category')->select('renovate_courses.*');
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
            'id',
            'course_categories_uuid' => ['title' => '課程類別', 'data' => 'category.name', 'name' => 'category.name'],
            'title'                  => ['title' => '標題'],
            'name'                   => ['title' => '課程名稱'],
            'code'                   => ['title' => '課程編號'],
            'description'            => ['title' => '描述'],
            'location'               => ['title' => '地點'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'renovate_courses_datatable_' . time();
    }
}
