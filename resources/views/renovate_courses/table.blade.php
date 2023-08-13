<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="renovate-courses-table">
            <thead>
            <tr>
                <th>Renovate Courses Uuid</th>
                <th>Title</th>
                <th>Name</th>
                <th>Code</th>
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
            @foreach($renovateCourses as $renovateCourse)
                <tr>
                    <td>{{ $renovateCourse->renovate_courses_uuid }}</td>
                    <td>{{ $renovateCourse->title }}</td>
                    <td>{{ $renovateCourse->name }}</td>
                    <td>{{ $renovateCourse->code }}</td>
                    <td>{{ $renovateCourse->short_desc }}</td>
                    <td>{{ $renovateCourse->description }}</td>
                    <td>{{ $renovateCourse->location }}</td>
                    <td>{{ $renovateCourse->created_by }}</td>
                    <td>{{ $renovateCourse->updated_by }}</td>
                    <td>{{ $renovateCourse->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['renovateCourses.destroy', $renovateCourse->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('renovateCourses.show', [$renovateCourse->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('renovateCourses.edit', [$renovateCourse->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $renovateCourses])
        </div>
    </div>
</div>
