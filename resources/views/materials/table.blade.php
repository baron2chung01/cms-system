<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="materials-table">
            <thead>
            <tr>
                <th>Materials Uuid</th>
                <th>Materials Categories Uuid</th>
                <th>Name</th>
                <th>Code</th>
                <th>Price</th>
                <th>Group Price</th>
                <th>Group Qty</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($materials as $materials)
                <tr>
                    <td>{{ $materials->materials_uuid }}</td>
                    <td>{{ $materials->materials_categories_uuid }}</td>
                    <td>{{ $materials->name }}</td>
                    <td>{{ $materials->code }}</td>
                    <td>{{ $materials->price }}</td>
                    <td>{{ $materials->group_price }}</td>
                    <td>{{ $materials->group_qty }}</td>
                    <td>{{ $materials->status }}</td>
                    <td>{{ $materials->created_by }}</td>
                    <td>{{ $materials->updated_by }}</td>
                    <td>{{ $materials->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['materials.destroy', $materials->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('materials.show', [$materials->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('materials.edit', [$materials->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $materials])
        </div>
    </div>
</div>
