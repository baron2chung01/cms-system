<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="assets-table">
            <thead>
            <tr>
                <th>Assets Uuid</th>
                <th>Module Uuid</th>
                <th>Resource Path</th>
                <th>File Name</th>
                <th>Type</th>
                <th>File Size</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($assets as $assets)
                <tr>
                    <td>{{ $assets->assets_uuid }}</td>
                    <td>{{ $assets->module_uuid }}</td>
                    <td>{{ $assets->resource_path }}</td>
                    <td>{{ $assets->file_name }}</td>
                    <td>{{ $assets->type }}</td>
                    <td>{{ $assets->file_size }}</td>
                    <td>{{ $assets->status }}</td>
                    <td>{{ $assets->created_by }}</td>
                    <td>{{ $assets->updated_by }}</td>
                    <td>{{ $assets->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['assets.destroy', $assets->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('assets.show', [$assets->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('assets.edit', [$assets->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $assets])
        </div>
    </div>
</div>
