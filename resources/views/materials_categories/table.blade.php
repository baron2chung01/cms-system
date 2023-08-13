<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="materials-categories-table">
            <thead>
            <tr>
                <th>Materials Categories Uuid</th>
                <th>Parents Uuid</th>
                <th>Code</th>
                <th>Name</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($materialsCategories as $materialsCategory)
                <tr>
                    <td>{{ $materialsCategory->materials_categories_uuid }}</td>
                    <td>{{ $materialsCategory->parents_uuid }}</td>
                    <td>{{ $materialsCategory->code }}</td>
                    <td>{{ $materialsCategory->name }}</td>
                    <td>{{ $materialsCategory->status }}</td>
                    <td>{{ $materialsCategory->created_by }}</td>
                    <td>{{ $materialsCategory->updated_by }}</td>
                    <td>{{ $materialsCategory->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['materialsCategories.destroy', $materialsCategory->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('materialsCategories.show', [$materialsCategory->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('materialsCategories.edit', [$materialsCategory->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $materialsCategories])
        </div>
    </div>
</div>
