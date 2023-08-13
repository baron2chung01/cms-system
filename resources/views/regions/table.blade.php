<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="regions-table">
            <thead>
            <tr>
                <th>Regions Uuid</th>
                <th>Name</th>
                <th>Code</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($regions as $region)
                <tr>
                    <td>{{ $region->regions_uuid }}</td>
                    <td>{{ $region->name }}</td>
                    <td>{{ $region->code }}</td>
                    <td>{{ $region->created_by }}</td>
                    <td>{{ $region->updated_by }}</td>
                    <td>{{ $region->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['regions.destroy', $region->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('regions.show', [$region->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('regions.edit', [$region->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $regions])
        </div>
    </div>
</div>
