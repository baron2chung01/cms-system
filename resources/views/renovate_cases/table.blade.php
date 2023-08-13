<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="renovate-cases-table">
            <thead>
            <tr>
                <th>Renovate Cases Uuid</th>
                <th>Title</th>
                <th>Name</th>
                <th>Short Desc</th>
                <th>Description</th>
                <th>Location</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($renovateCases as $renovateCases)
                <tr>
                    <td>{{ $renovateCases->renovate_cases_uuid }}</td>
                    <td>{{ $renovateCases->title }}</td>
                    <td>{{ $renovateCases->name }}</td>
                    <td>{{ $renovateCases->short_desc }}</td>
                    <td>{{ $renovateCases->description }}</td>
                    <td>{{ $renovateCases->location }}</td>
                    <td>{{ $renovateCases->created_by }}</td>
                    <td>{{ $renovateCases->updated_by }}</td>
                    <td>{{ $renovateCases->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['renovateCases.destroy', $renovateCases->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('renovateCases.show', [$renovateCases->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('renovateCases.edit', [$renovateCases->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $renovateCases])
        </div>
    </div>
</div>
